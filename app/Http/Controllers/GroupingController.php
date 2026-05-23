<?php
// app/Http/Controllers/GroupingController.php

namespace App\Http\Controllers;

use App\Models\CalonSiswa;
use App\Models\GroupingRequest;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupingController extends BaseController
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * List semua grouping request
     */
    public function index(Request $request)
    {
        $query = GroupingRequest::with(['students', 'tahunAjaran', 'creator', 'approver']);

        // Filter status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        } else {
            // Default tampilkan pending dulu
            $query->where('status', 'pending');
        }

        // Filter tahun ajaran
        if ($request->has('tahun') && $request->tahun != '') {
            $query->where('tahun_ajaran_id', $request->tahun);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('request_code', 'like', "%{$search}%")
                    ->orWhere('group_name', 'like', "%{$search}%");
            });
        }

        $groupings = $query->orderBy('created_at', 'desc')->paginate(20);
        $tahunAjaran = TahunAjaran::orderBy('tahun_ajaran', 'desc')->get();

        // Statistik untuk sidebar/filter
        $stats = [
            'pending' => GroupingRequest::where('status', 'pending')->count(),
            'approved' => GroupingRequest::where('status', 'approved')->count(),
            'rejected' => GroupingRequest::where('status', 'rejected')->count(),
            'processing' => GroupingRequest::where('status', 'processing')->count(),
            'total' => GroupingRequest::count(),
        ];

        return view('admin.groupings.index', compact('groupings', 'tahunAjaran', 'stats'));
    }

    /**
     * Detail grouping request
     */
    public function show($id)
    {
        $grouping = GroupingRequest::with(['students', 'tahunAjaran', 'creator', 'approver'])
            ->findOrFail($id);

        // Ambil siswa yang tidak dalam grup apapun untuk ditambahkan (opsional)
        $availableStudents = CalonSiswa::where('tahun_ajaran_id', $grouping->tahun_ajaran_id)
            ->whereNull('grouping_request_id')
            ->where(function ($q) {
                $q->whereNull('requested_with_names')
                    ->orWhere('requested_with_names', '');
            })
            ->orderBy('nama_lengkap')
            ->get();

        return view('admin.groupings.show', compact('grouping', 'availableStudents'));
    }

    /**
     * Form create grouping manual
     */
    public function create()
    {
        $tahunAjaranAktif = TahunAjaran::where('status', 'aktif')->first();

        if (!$tahunAjaranAktif) {
            return redirect()->back()->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        $students = CalonSiswa::where('tahun_ajaran_id', $tahunAjaranAktif->id)
            ->whereNull('grouping_request_id')
            ->orderBy('nama_lengkap')
            ->get();

        // Kelompokkan siswa yang punya request
        $studentsWithRequest = $students->filter(function ($student) {
            return !empty($student->requested_with_names);
        });

        $studentsWithoutRequest = $students->filter(function ($student) {
            return empty($student->requested_with_names);
        });

        return view('admin.groupings.create', compact('tahunAjaranAktif', 'studentsWithRequest', 'studentsWithoutRequest'));
    }

    /**
     * Store grouping manual
     */
    public function store(Request $request)
    {
        $request->validate([
            'group_name' => 'required|string|max:100',
            'student_ids' => 'required|array|min:2',
            'student_ids.*' => 'exists:calon_siswas,id',
            'notes' => 'nullable|string',
        ]);

        $students = CalonSiswa::whereIn('id', $request->student_ids)->get();
        $tahunAjaranId = $students->first()->tahun_ajaran_id;

        // Cek apakah semua siswa satu tahun ajaran
        $differentTahun = $students->where('tahun_ajaran_id', '!=', $tahunAjaranId);
        if ($differentTahun->count() > 0) {
            return back()->with('error', 'Semua siswa harus dari tahun ajaran yang sama.');
        }

        DB::beginTransaction();
        try {
            $grouping = GroupingRequest::create([
                'request_code' => GroupingRequest::generateCode(),
                'group_name' => $request->group_name,
                'tahun_ajaran_id' => $tahunAjaranId,
                'status' => 'pending',
                'notes' => $request->notes,
                'created_by' => Auth::id(),
            ]);

            // Update siswa
            CalonSiswa::whereIn('id', $request->student_ids)->update([
                'grouping_request_id' => $grouping->id,
                'grouping_priority' => 'high',
                'requested_with_names' => null // Clear request
            ]);

            DB::commit();

            return redirect()->route('groupings.show', $grouping->id)
                ->with('success', 'Grouping request berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat grouping: ' . $e->getMessage());
        }
    }

    /**
     * Approve grouping request
     */
    public function approve($id)
    {
        $grouping = GroupingRequest::findOrFail($id);

        if ($grouping->status !== 'pending') {
            return back()->with('error', 'Request ini sudah diproses sebelumnya.');
        }

        $grouping->approve(Auth::id());

        return redirect()->route('groupings.index')
            ->with('success', "Grouping '{$grouping->group_name}' telah disetujui.");
    }

    /**
     * Reject grouping request
     */
    public function reject($id)
    {
        $grouping = GroupingRequest::findOrFail($id);

        if ($grouping->status !== 'pending') {
            return back()->with('error', 'Request ini sudah diproses sebelumnya.');
        }

        $grouping->reject();

        return redirect()->route('groupings.index')
            ->with('success', "Grouping '{$grouping->group_name}' telah ditolak.");
    }

    /**
     * Hapus grouping (soft delete atau force)
     */
    public function destroy($id)
    {
        $grouping = GroupingRequest::findOrFail($id);

        DB::beginTransaction();
        try {
            // Remove grouping from students
            CalonSiswa::where('grouping_request_id', $id)->update([
                'grouping_request_id' => null,
                'grouping_priority' => 'none'
            ]);

            $grouping->delete();

            DB::commit();

            return redirect()->route('groupings.index')
                ->with('success', 'Grouping berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus grouping: ' . $e->getMessage());
        }
    }

    /**
     * Add student to existing grouping
     */
    public function addStudent(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required|exists:calon_siswas,id',
        ]);

        $grouping = GroupingRequest::findOrFail($id);
        $student = CalonSiswa::findOrFail($request->student_id);

        // Cek tahun ajaran
        if ($student->tahun_ajaran_id != $grouping->tahun_ajaran_id) {
            return back()->with('error', 'Siswa harus dari tahun ajaran yang sama dengan grup.');
        }

        // Cek apakah sudah di grup lain
        if ($student->grouping_request_id) {
            return back()->with('error', 'Siswa sudah tergabung dalam grup lain.');
        }

        $student->update([
            'grouping_request_id' => $grouping->id,
            'grouping_priority' => 'high',
            'requested_with_names' => null
        ]);

        return back()->with('success', 'Siswa berhasil ditambahkan ke grup.');
    }

    /**
     * Remove student from grouping
     */
    public function removeStudent($id, $studentId)
    {
        $grouping = GroupingRequest::findOrFail($id);
        $student = CalonSiswa::findOrFail($studentId);

        if ($student->grouping_request_id != $grouping->id) {
            return back()->with('error', 'Siswa tidak tergabung dalam grup ini.');
        }

        $student->update([
            'grouping_request_id' => null,
            'grouping_priority' => 'none'
        ]);

        return back()->with('success', 'Siswa berhasil dikeluarkan dari grup.');
    }

    /**
     * Bulk approve multiple groupings
     */
    public function bulkApprove(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids)) {
            $ids = array_filter(array_map('trim', explode(',', $ids)));
            $request->merge(['ids' => $ids]);
        }

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:grouping_requests,id',
        ]);

        $count = 0;
        foreach ($request->ids as $id) {
            $grouping = GroupingRequest::find($id);
            if ($grouping && $grouping->status === 'pending') {
                $grouping->approve(Auth::id());
                $count++;
            }
        }

        return redirect()->route('groupings.index')
            ->with('success', "{$count} grouping request berhasil disetujui.");
    }

    /**
     * Bulk reject multiple groupings
     */
    public function bulkReject(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids)) {
            $ids = array_filter(array_map('trim', explode(',', $ids)));
            $request->merge(['ids' => $ids]);
        }

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:grouping_requests,id',
        ]);

        $count = 0;
        foreach ($request->ids as $id) {
            $grouping = GroupingRequest::find($id);
            if ($grouping && $grouping->status === 'pending') {
                $grouping->reject();
                $count++;
            }
        }

        return redirect()->route('groupings.index')
            ->with('success', "{$count} grouping request berhasil ditolak.");
    }
}
