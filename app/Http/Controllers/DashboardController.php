<?php

namespace App\Http\Controllers;

use App\Models\CalonSiswa;
use App\Models\GroupingRequest;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Ambil tahun ajaran aktif
        $tahunAjaranAktif = TahunAjaran::where('status', 'aktif')
            ->withCount('calonSiswa')
            ->first();

        // Statistik umum
        $totalPendaftar = CalonSiswa::count();
        $totalTahunAjaran = TahunAjaran::count();

        // Data untuk grafik per tahun ajaran
        $dataTahunAjaran = TahunAjaran::withCount('calonSiswa')
            ->orderBy('tahun_ajaran', 'desc')
            ->take(5)
            ->get();

        $chartLabels = $dataTahunAjaran->pluck('tahun_ajaran');
        $chartData = $dataTahunAjaran->pluck('calon_siswa_count');

        // Data pendaftar per bulan (untuk tahun aktif) - Optimized dengan raw query
        $pendaftarPerBulan = [];
        if ($tahunAjaranAktif) {
            $bulanData = CalonSiswa::where('tahun_ajaran_id', $tahunAjaranAktif->id)
                ->selectRaw("MONTH(created_at) as bulan, COUNT(*) as jumlah")
                ->groupByRaw("MONTH(created_at)")
                ->pluck('jumlah', 'bulan')
                ->toArray();

            for ($i = 1; $i <= 12; $i++) {
                $bulan = date('F', mktime(0, 0, 0, $i, 1));
                $pendaftarPerBulan[$bulan] = $bulanData[$i] ?? 0;
            }
        }

        // Data untuk tabel recent pendaftar
        $recentPendaftar = CalonSiswa::with('tahunAjaran')
            ->latest()
            ->take(5)
            ->get();

        // ========== FITUR AUTO-DETECT MUTUAL REQUEST ==========
        // Hanya untuk admin
        $groupingStats = [];
        $mutualRequests = [];
        $pendingGroupings = collect();

        // Cek apakah user adalah admin (asumsikan ada field 'role' atau similar)
        // Sesuaikan dengan struktur user Anda
        $isAdmin = auth()->user()->role === 'admin';

        if ($isAdmin) {
            // 1. Statistik grouping
            $groupingStats = $this->getGroupingStats();

            // 2. Data mutual requests yang terdeteksi
            $mutualRequests = $this->detectMutualRequests($request);

            // 3. Pending groupings untuk ditampilkan
            $pendingGroupings = GroupingRequest::with(['students', 'tahunAjaran'])
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        }

        return view('dashboard.index', compact(
            'tahunAjaranAktif',
            'totalPendaftar',
            'totalTahunAjaran',
            'chartLabels',
            'chartData',
            'pendaftarPerBulan',
            'recentPendaftar',
            'groupingStats',      // TAMBAHKAN
            'mutualRequests',     // TAMBAHKAN
            'pendingGroupings'    // TAMBAHKAN
        ));
    }

    // Method untuk statistik grouping (sudah ada)
    public function getGroupingStats()
    {
        $pendingGroups = GroupingRequest::where('status', 'pending')->count();
        $pendingRequests = CalonSiswa::whereNotNull('requested_with_names')->count();

        // Hitung mutual request yang terdeteksi
        $mutualDetected = CalonSiswa::whereNotNull('requested_with_names')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('calon_siswas as cs2')
                    ->whereRaw('cs2.requested_with_names LIKE CONCAT("%", calon_siswas.nama_lengkap, "%")')
                    ->whereColumn('cs2.tahun_ajaran_id', 'calon_siswas.tahun_ajaran_id');
            })->count();

        // Hitung total siswa yang sudah tergabung dalam grouping
        $totalGrouped = CalonSiswa::whereNotNull('grouping_request_id')->count();

        // Persentase request yang sudah diproses
        $totalRequests = $pendingRequests + GroupingRequest::count();
        $processedRequests = $totalRequests > 0 ? round(($pendingGroups / $totalRequests) * 100) : 0;

        return compact('pendingGroups', 'pendingRequests', 'mutualDetected', 'totalGrouped', 'processedRequests');
    }

    // Method baru: Detect mutual requests dengan detail
    public function detectMutualRequests(Request $request)
    {
        $tahunAjaranId = $request->get('tahun', null);

        $query = CalonSiswa::whereNotNull('requested_with_names')
            ->where('requested_with_names', '!=', '')
            ->with('tahunAjaran');

        // Filter berdasarkan tahun ajaran jika ada
        if ($tahunAjaranId) {
            $query->where('tahun_ajaran_id', $tahunAjaranId);
        }

        $studentsWithRequests = $query->get();

        $mutualPairs = [];

        foreach ($studentsWithRequests as $student) {
            $requestedNames = explode('|', $student->requested_with_names);
            $requestedNames = array_map('trim', $requestedNames);
            $requestedNames = array_map('strtoupper', $requestedNames);

            // Cari siswa yang namanya ada di requested_names dan juga meminta balik
            foreach ($studentsWithRequests as $potentialMatch) {
                if ($student->id === $potentialMatch->id) continue;

                // Cek apakah student meminta potentialMatch
                $studentWantsMatch = in_array(strtoupper($potentialMatch->nama_lengkap), $requestedNames);

                // Cek apakah potentialMatch meminta student
                $matchWantsStudent = false;
                if ($potentialMatch->requested_with_names) {
                    $matchRequestedNames = explode('|', $potentialMatch->requested_with_names);
                    $matchRequestedNames = array_map('trim', $matchRequestedNames);
                    $matchRequestedNames = array_map('strtoupper', $matchRequestedNames);
                    $matchWantsStudent = in_array(strtoupper($student->nama_lengkap), $matchRequestedNames);
                }

                // Jika mutual request terdeteksi
                if ($studentWantsMatch && $matchWantsStudent) {
                    $pairKey = min($student->id, $potentialMatch->id) . '-' . max($student->id, $potentialMatch->id);

                    if (!isset($mutualPairs[$pairKey])) {
                        $mutualPairs[$pairKey] = [
                            'student1' => $student,
                            'student2' => $potentialMatch,
                            'other_names' => array_diff($requestedNames, [strtoupper($potentialMatch->nama_lengkap)]),
                            'detected_at' => now()
                        ];
                    }
                }
            }
        }

        // Convert ke array untuk view
        return array_values($mutualPairs);
    }

    // API endpoint untuk AJAX refresh mutual requests
    public function apiMutualRequests(Request $request)
    {
        $mutualRequests = $this->detectMutualRequests($request);

        return response()->json([
            'success' => true,
            'data' => $mutualRequests,
            'count' => count($mutualRequests)
        ]);
    }

    // API endpoint untuk create grouping dari mutual request
    public function createGroupingFromMutual(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array|min:2',
            'student_ids.*' => 'exists:calon_siswas,id',
            'group_name' => 'nullable|string|max:100'
        ]);

        $students = CalonSiswa::whereIn('id', $request->student_ids)->get();

        if ($students->count() < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal 2 siswa untuk membuat grouping'
            ], 422);
        }

        $tahunAjaranId = $students->first()->tahun_ajaran_id;
        $groupName = $request->group_name ?? 'Mutual Request: ' . $students->pluck('nama_lengkap')->implode(', ');

        DB::beginTransaction();
        try {
            $grouping = GroupingRequest::create([
                'request_code' => GroupingRequest::generateCode(),
                'group_name' => $groupName,
                'tahun_ajaran_id' => $tahunAjaranId,
                'status' => 'pending',
                'notes' => 'Auto-detected mutual request - ' . now()->format('d/m/Y H:i'),
                'created_by' => auth()->id(),
            ]);

            // Update semua siswa dalam grup
            CalonSiswa::whereIn('id', $request->student_ids)->update([
                'grouping_request_id' => $grouping->id,
                'grouping_priority' => 'high',
                'requested_with_names' => null // Clear karena sudah diproses
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Grouping berhasil dibuat',
                'redirect_url' => route('groupings.show', $grouping->id)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat grouping: ' . $e->getMessage()
            ], 500);
        }
    }
}
