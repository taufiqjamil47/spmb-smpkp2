<?php

namespace App\Http\Controllers;

use App\Exports\CalonSiswaCsvExport;
use App\Exports\CalonSiswaExport;
use App\Models\CalonSiswa;
use App\Models\GroupingRequest;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class PendaftaranController extends Controller
{
    public function index(Request $request)
    {
        $query = CalonSiswa::with('tahunAjaran');

        // Fitur pencarian
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('no_peserta', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan tahun ajaran
        if ($request->has('tahun') && $request->tahun != '') {
            $query->where('tahun_ajaran_id', $request->tahun);
        }

        $pendaftar = $query->latest()->paginate(15);

        // Ambil data tahun ajaran untuk filter
        $tahunAjaran = TahunAjaran::orderBy('tahun_ajaran', 'desc')->get();

        // Get trash count once instead of querying in view
        $trashCount = CalonSiswa::onlyTrashed()->count();

        return view('pendaftaran.index', compact('pendaftar', 'tahunAjaran', 'trashCount'));
    }

    public function create()
    {
        $tahunAjaranAktif = TahunAjaran::where('status', 'aktif')->first();

        if (!$tahunAjaranAktif) {
            return redirect()->route('dashboard')
                ->with('error', 'Belum ada tahun ajaran yang aktif. Silakan hubungi admin.');
        }

        // Cek kuota
        $jumlahPendaftar = CalonSiswa::where('tahun_ajaran_id', $tahunAjaranAktif->id)->count();
        if ($jumlahPendaftar >= $tahunAjaranAktif->kuota) {
            return redirect()->route('dashboard')
                ->with('error', 'Maaf, kuota pendaftaran tahun ini sudah penuh.');
        }

        return view('pendaftaran.create', compact('tahunAjaranAktif'));
    }

    public function store(Request $request)
    {
        $tahunAjaranAktif = TahunAjaran::where('status', 'aktif')->first();

        if (!$tahunAjaranAktif) {
            return redirect()->back()->with('error', 'Tahun ajaran belum diatur.');
        }

        // Validasi data
        $request->validate([
            // Data wajib
            'nama_lengkap' => 'required|min:3',
            'nisn' => 'required|digits:10|unique:calon_siswas,nisn',
            'nik' => 'required|digits:16|unique:calon_siswas,nik',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required',
            'agama' => 'required',
            'alamat' => 'required',
            'sekolah_asal' => 'required',
            'tahun_lulus' => 'required|digits:4',
            'nama_ayah' => 'required',
            'nama_ibu' => 'required',
            'no_hp_siswa' => 'required',
            // 'no_telp' => 'required',
            'no_hp_ortu' => 'required',

            // Validasi numerik
            'rt' => 'nullable|numeric',
            'rw' => 'nullable|numeric',
            'tinggi_badan' => 'nullable|numeric',
            'berat_badan' => 'nullable|numeric',
            'anak_ke' => 'nullable|numeric',

            // Validasi tahun
            'tahun_lahir_ayah' => 'nullable|digits:4',
            'tahun_lahir_ibu' => 'nullable|digits:4',
            'tahun_lahir_wali' => 'nullable|digits:4',

            'requested_with_names_raw' => 'nullable|string|max:500',
            'grouping_priority' => 'nullable|in:none,medium,high',
        ], [
            'nisn.unique' => 'NISN sudah terdaftar',
            'nik.unique' => 'NIK sudah terdaftar',
            'nisn.digits' => 'NISN harus berupa 10 digit angka.',
            'nik.digits' => 'NIK harus berupa 16 digit angka.',
            'no_hp_siswa.required' => 'No. HP Siswa wajib diisi.',
            'no_telp.required' => 'No. Telepon Rumah wajib diisi.',
            'no_hp_ortu.required' => 'No. HP Orang Tua/Wali wajib diisi.',
        ]);

        // Generate nomor peserta otomatis
        $tahun = date('Y');
        $lastSiswa = CalonSiswa::withTrashed()
            ->whereYear('created_at', $tahun)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastSiswa) {
            $lastNumber = intval(substr($lastSiswa->no_peserta, -3));
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        $no_peserta = 'SPMBKP2-' . $tahun . '-' . $newNumber;

        // Proses requested names (ubah format dari koma menjadi pipe untuk penyimpanan)
        $requestedWithNames = null;
        if ($request->filled('requested_with_names_raw')) {
            // Hapus spasi berlebih dan ubah ke uppercase
            $names = array_map('trim', explode(',', $request->requested_with_names_raw));
            $names = array_map('strtoupper', $names);
            $requestedWithNames = implode('|', $names);
        }

        $alamatAsli = $request->alamat;
        // Cek apakah diawali dengan "Kp." atau "Kp" (abaikan spasi awal dan case)
        $trimmedAlamat = ltrim($alamatAsli);
        if (!preg_match('/^kp\.?/i', $trimmedAlamat)) {
            // Jika tidak diawali Kp. atau Kp, tambahkan "Kp. "
            $alamatFinal = 'Kp. ' . ucfirst($alamatAsli);
        } else {
            $alamatFinal = ucfirst($alamatAsli);
        }

        // Siapkan data untuk disimpan
        $data = [
            'no_peserta' => $no_peserta,
            'tahun_ajaran_id' => $tahunAjaranAktif->id,
            'periode' => $tahunAjaranAktif->tahun_ajaran,

            // Data pribadi
            'nama_lengkap' => strtoupper($request->nama_lengkap),
            'nisn' => $request->nisn,
            'nik' => $request->nik,
            'tempat_lahir' => strtoupper($request->tempat_lahir),
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama' => $request->agama,

            // Alamat
            'alamat' => $alamatFinal,
            'rt' => $request->rt,
            'rw' => $request->rw,
            'desa' => strtoupper($request->desa),
            'kecamatan' => strtoupper($request->kecamatan),

            // Kontak
            'no_hp_siswa' => $request->no_hp_siswa,
            // 'no_telp' => $request->no_telp,

            // Sekolah
            'sekolah_asal' => strtoupper($request->sekolah_asal),
            'tahun_lulus' => $request->tahun_lulus,

            // Kesehatan
            'tinggi_badan' => $request->tinggi_badan,
            'berat_badan' => $request->berat_badan,
            'anak_ke' => $request->anak_ke,
            'ukuran_baju' => $request->ukuran_baju,

            // Bantuan
            'pkh' => $request->pkh,
            'kks' => $request->kks,
            'pip' => $request->pip,

            // Ayah
            'nama_ayah' => strtoupper($request->nama_ayah),
            'tahun_lahir_ayah' => $request->tahun_lahir_ayah,
            'pekerjaan_ayah' => $request->pekerjaan_ayah,
            'pendidikan_ayah' => $request->pendidikan_ayah,

            // Ibu
            'nama_ibu' => strtoupper($request->nama_ibu),
            'tahun_lahir_ibu' => $request->tahun_lahir_ibu,
            'pekerjaan_ibu' => $request->pekerjaan_ibu,
            'pendidikan_ibu' => $request->pendidikan_ibu,

            // Wali
            'nama_wali' => strtoupper($request->nama_wali),
            'tahun_lahir_wali' => $request->tahun_lahir_wali,
            'pekerjaan_wali' => $request->pekerjaan_wali,
            'pendidikan_wali' => $request->pendidikan_wali,

            // Kontak orang tua
            'no_hp_ortu' => $request->no_hp_ortu,
            'requested_with_names' => $requestedWithNames,
            'grouping_priority' => $request->grouping_priority,
        ];

        // Simpan data
        $calonSiswa = CalonSiswa::create($data);

        // Buat pending grouping request untuk request satu kelas
        if ($requestedWithNames) {
            $this->createPendingGroupingForStudent($calonSiswa, $request->grouping_priority ?? 'high');
        }

        // Cek apakah ada request yang mutual (saling request)
        $this->checkMutualRequests($calonSiswa);

        return redirect()->route('pendaftaran.show', $calonSiswa->id)
            ->with('success', 'Pendaftaran berhasil! Nomor peserta: ' . $no_peserta);
    }

    public function show($id)
    {
        $pendaftar = CalonSiswa::withTrashed()
            ->with('tahunAjaran')
            ->findOrFail($id);

        return view('pendaftaran.show', compact('pendaftar'));
    }

    public function edit($id)
    {
        // Hanya admin yang bisa edit
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $pendaftar = CalonSiswa::withTrashed()->findOrFail($id);
        $tahunAjaran = TahunAjaran::orderBy('tahun_ajaran', 'desc')->get();

        return view('pendaftaran.edit', compact('pendaftar', 'tahunAjaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Hanya admin yang bisa update
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        $pendaftar = CalonSiswa::withTrashed()->findOrFail($id);

        $alamatAsli = $request->alamat;
        // Cek apakah diawali dengan "Kp." atau "Kp" (abaikan spasi awal dan case)
        $trimmedAlamat = ltrim($alamatAsli);
        if (!preg_match('/^kp\.?/i', $trimmedAlamat)) {
            // Jika tidak diawali Kp. atau Kp, tambahkan "Kp. "
            $alamatFinal = 'Kp. ' . ucfirst($alamatAsli);
        } else {
            $alamatFinal = ucfirst($alamatAsli);
        }

        // Validasi data
        $request->validate([
            // Data wajib
            'nama_lengkap' => 'required|min:3',
            'nisn' => 'required|max:10|unique:calon_siswas,nisn,' . $id,
            'nik' => 'required|max:16|unique:calon_siswas,nik,' . $id,
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required',
            'agama' => 'required',
            'alamat' => 'required',
            'sekolah_asal' => 'required',
            'tahun_lulus' => 'required|digits:4',
            'nama_ayah' => 'required',
            'nama_ibu' => 'required',
            'no_hp_ortu' => 'required',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',

            // Validasi numerik
            'rt' => 'nullable|numeric',
            'rw' => 'nullable|numeric',
            'tinggi_badan' => 'nullable|numeric',
            'berat_badan' => 'nullable|numeric',
            'anak_ke' => 'nullable|numeric',

            // Validasi tahun
            'tahun_lahir_ayah' => 'nullable|digits:4',
            'tahun_lahir_ibu' => 'nullable|digits:4',
            'tahun_lahir_wali' => 'nullable|digits:4',
        ]);

        // Siapkan data untuk update
        $data = [
            'tahun_ajaran_id' => $request->tahun_ajaran_id,
            'periode' => TahunAjaran::find($request->tahun_ajaran_id)->tahun_ajaran,

            // Data pribadi
            'nama_lengkap' => strtoupper($request->nama_lengkap),
            'nisn' => $request->nisn,
            'nik' => $request->nik,
            'tempat_lahir' => strtoupper($request->tempat_lahir),
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama' => $request->agama,

            // Alamat
            'alamat' => $alamatFinal,
            'rt' => $request->rt,
            'rw' => $request->rw,
            'desa' => strtoupper($request->desa),
            'kecamatan' => strtoupper($request->kecamatan),

            // Kontak
            'no_hp_siswa' => $request->no_hp_siswa,
            // 'no_telp' => $request->no_telp,

            // Sekolah
            'sekolah_asal' => strtoupper($request->sekolah_asal),
            'tahun_lulus' => $request->tahun_lulus,

            // Kesehatan
            'tinggi_badan' => $request->tinggi_badan,
            'berat_badan' => $request->berat_badan,
            'anak_ke' => $request->anak_ke,
            'ukuran_baju' => $request->ukuran_baju,

            // Bantuan
            'pkh' => $request->pkh,
            'kks' => $request->kks,
            'pip' => $request->pip,

            // Ayah
            'nama_ayah' => strtoupper($request->nama_ayah),
            'tahun_lahir_ayah' => $request->tahun_lahir_ayah,
            'pekerjaan_ayah' => $request->pekerjaan_ayah,
            'pendidikan_ayah' => $request->pendidikan_ayah,

            // Ibu
            'nama_ibu' => strtoupper($request->nama_ibu),
            'tahun_lahir_ibu' => $request->tahun_lahir_ibu,
            'pekerjaan_ibu' => $request->pekerjaan_ibu,
            'pendidikan_ibu' => $request->pendidikan_ibu,

            // Wali
            'nama_wali' => strtoupper($request->nama_wali),
            'tahun_lahir_wali' => $request->tahun_lahir_wali,
            'pekerjaan_wali' => $request->pekerjaan_wali,
            'pendidikan_wali' => $request->pendidikan_wali,

            // Kontak orang tua
            'no_hp_ortu' => $request->no_hp_ortu,
        ];

        // Update data
        $pendaftar->update($data);

        return redirect()->route('pendaftaran.show', $pendaftar->id)
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function cetakKartu($id)
    {
        $pendaftar = CalonSiswa::withTrashed()->with('tahunAjaran')->findOrFail($id);
        return view('pendaftaran.cetak', compact('pendaftar'));
    }

    public function trash(Request $request)
    {
        // Ambil data yang sudah dihapus (soft deleted)
        $query = CalonSiswa::onlyTrashed()->with('tahunAjaran');

        // Fitur pencarian
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('no_peserta', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        // Urutkan dan paginasi
        $pendaftar = $query->orderBy('deleted_at', 'desc')->paginate(15);

        // Pastikan view ada
        return view('pendaftaran.trash', compact('pendaftar'));
    }

    public function destroy($id)
    {
        $calonSiswa = CalonSiswa::findOrFail($id);

        // Hanya admin yang bisa hapus
        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menghapus data.'
            ], 403);
        }

        // Soft delete
        $calonSiswa->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data siswa berhasil dipindahkan ke trash.'
            ]);
        }

        return redirect()->route('pendaftaran.index')
            ->with('success', 'Data siswa berhasil dipindahkan ke trash.');
    }

    public function restore($id)
    {
        // Hanya admin yang bisa restore
        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengembalikan data.'
            ], 403);
        }

        $calonSiswa = CalonSiswa::onlyTrashed()->with('tahunAjaran')->findOrFail($id);
        $calonSiswa->restore();

        // Cek kuota tahun ajaran
        $tahunAjaran = $calonSiswa->tahunAjaran;
        $jumlahPendaftar = CalonSiswa::where('tahun_ajaran_id', $tahunAjaran->id)->count();

        if ($jumlahPendaftar > $tahunAjaran->kuota) {
            // Jika melebihi kuota, beri peringatan
            session()->flash('warning', 'Perhatian: Jumlah pendaftar di tahun ajaran ini sekarang melebihi kuota!');
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data siswa berhasil dikembalikan.'
            ]);
        }

        return redirect()->route('pendaftaran.trash')
            ->with('success', 'Data siswa berhasil dikembalikan.');
    }

    /**
     * Permanently delete record from storage.
     */
    public function forceDelete($id)
    {
        // Hanya admin yang bisa force delete
        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menghapus data permanen.'
            ], 403);
        }

        $calonSiswa = CalonSiswa::onlyTrashed()->with('dokumen')->findOrFail($id);

        // Hapus data terkait (dokumen, dll) jika ada - Optimized dengan eager loading
        $dokumenList = $calonSiswa->dokumen;
        if ($dokumenList->count() > 0) {
            // Hapus file fisik untuk semua dokumen yang sudah di-load
            foreach ($dokumenList as $dokumen) {
                if (file_exists(public_path($dokumen->path))) {
                    unlink(public_path($dokumen->path));
                }
            }
            // Hapus record dokumen
            $calonSiswa->dokumen()->delete();
        }

        // Force delete
        $calonSiswa->forceDelete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data siswa berhasil dihapus permanen.'
            ]);
        }

        return redirect()->route('pendaftaran.trash')
            ->with('success', 'Data siswa berhasil dihapus permanen.');
    }

    /**
     * Restore all trashed records.
     */
    public function restoreAll()
    {
        // Hanya admin yang bisa restore all
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $count = CalonSiswa::onlyTrashed()->count();
        CalonSiswa::onlyTrashed()->restore();

        return redirect()->route('pendaftaran.trash')
            ->with('success', "{$count} data siswa berhasil dikembalikan.");
    }

    /**
     * Empty trash (force delete all).
     */
    public function emptyTrash()
    {
        // Hanya admin yang bisa empty trash
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $trashedItems = CalonSiswa::onlyTrashed()->get();
        $count = $trashedItems->count();

        DB::transaction(function () use ($trashedItems) {
            foreach ($trashedItems as $item) {
                // Hapus file dokumen jika ada
                if ($item->dokumen()->count() > 0) {
                    foreach ($item->dokumen as $dokumen) {
                        if (file_exists(public_path($dokumen->path))) {
                            unlink(public_path($dokumen->path));
                        }
                    }
                    $item->dokumen()->delete();
                }
                $item->forceDelete();
            }
        });

        return redirect()->route('pendaftaran.index')
            ->with('success', "{$count} data siswa berhasil dihapus permanen.");
    }

    public function exportExcel(Request $request)
    {
        // Hanya admin yang bisa export
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk export data.');
        }

        $tahunAjaranId = $request->tahun;
        $status = $request->status; // 'aktif', 'trash', 'all'

        $fileName = 'pendaftar-spmb-kp2';

        // Tambahkan info tahun ke nama file
        if ($tahunAjaranId) {
            $tahun = TahunAjaran::find($tahunAjaranId);
            $fileName .= '-' . ($tahun ? $tahun->tahun_ajaran : '');
        }

        // Tambahkan info status
        if ($status == 'trash') {
            $fileName .= '-terhapus';
        } elseif ($status == 'all') {
            $fileName .= '-all';
        }

        $fileName .= '-' . date('Y-m-d-His') . '.xlsx';

        return Excel::download(
            new CalonSiswaExport($tahunAjaranId, $status),
            $fileName
        );
    }

    /**
     * Export data ke CSV
     */
    public function exportCsv(Request $request)
    {
        // Hanya admin yang bisa export
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk export data.');
        }

        $tahunAjaranId = $request->tahun;
        $status = $request->status;

        $fileName = 'pendaftar-spmb-kp2';

        if ($tahunAjaranId) {
            $tahun = TahunAjaran::find($tahunAjaranId);
            $fileName .= '-' . ($tahun ? $tahun->tahun_ajaran : '');
        }

        if ($status == 'trash') {
            $fileName .= '-terhapus';
        } elseif ($status == 'all') {
            $fileName .= '-all';
        }

        $fileName .= '-' . date('Y-m-d-His') . '.csv';

        return Excel::download(
            new CalonSiswaCsvExport($tahunAjaranId, $status),
            $fileName
        );
    }

    /**
     * Export template untuk import (jika diperlukan)
     */
    public function exportTemplate()
    {
        $headers = [
            'NO_PESERTA',
            'NISN',
            'NAMA_LENGKAP',
            'TEMPAT_LAHIR',
            'TANGGAL_LAHIR (Y-m-d)',
            'JENIS_KELAMIN (L/P)',
            'AGAMA',
            'ALAMAT',
            'NO_HP_SISWA',
            'SEKOLAH_ASAL',
            'TAHUN_LULUS',
            'NAMA_AYAH',
            'NAMA_IBU',
            'PEKERJAAN_ORTU',
            'NO_HP_ORTU'
        ];

        $data = [
            [
                'PPDB-2024-0001',
                '1234567890',
                'CONTOH: BUDI SANTOSO',
                'Jakarta',
                '2010-01-01',
                'L',
                'Islam',
                'Jl. Contoh No. 123',
                '081234567890',
                'SDN Contoh 01',
                '2024',
                'Ahmad Santoso',
                'Siti Aminah',
                'Wiraswasta',
                '081234567891'
            ]
        ];

        return Excel::download(
            new class($headers, $data) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings {
                protected $headers;
                protected $data;

                public function __construct($headers, $data)
                {
                    $this->headers = $headers;
                    $this->data = $data;
                }

                public function array(): array
                {
                    return $this->data;
                }

                public function headings(): array
                {
                    return $this->headers;
                }
            },
            'template-import-ppdb.xlsx'
        );
    }

    private function createPendingGroupingForStudent(CalonSiswa $student, string $priority = 'high')
    {
        if (!$student->requested_with_names) {
            return null;
        }

        $requestedNames = array_filter(array_map('trim', explode('|', $student->requested_with_names)));
        if (empty($requestedNames)) {
            return null;
        }

        $groupCode = GroupingRequest::generateCode();
        $groupName = 'Request: ' . $student->nama_lengkap;
        $notes = 'Request satu kelas dengan: ' . implode(', ', $requestedNames);

        $groupRequest = GroupingRequest::create([
            'request_code' => $groupCode,
            'group_name' => $groupName,
            'tahun_ajaran_id' => $student->tahun_ajaran_id,
            'status' => 'pending',
            'notes' => $notes,
            'created_by' => Auth::id(),
        ]);

        $student->update([
            'grouping_request_id' => $groupRequest->id,
            'grouping_priority' => $priority,
        ]);

        return $groupRequest;
    }

    // Tambahkan method helper
    private function checkMutualRequests($newStudent)
    {
        if (!$newStudent->requested_with_names) {
            return;
        }

        $requestedNames = array_filter(array_map('trim', explode('|', $newStudent->requested_with_names)));
        if (empty($requestedNames)) {
            return;
        }

        // Cari siswa yang sudah mendaftar dan mutual request
        $mutualStudents = CalonSiswa::where('tahun_ajaran_id', $newStudent->tahun_ajaran_id)
            ->where('id', '!=', $newStudent->id)
            ->where(function ($query) use ($newStudent) {
                $query->where('requested_with_names', 'like', '%' . $newStudent->nama_lengkap . '%');
            })
            ->get();

        if ($mutualStudents->isEmpty()) {
            return;
        }

        $groupRequest = null;
        $existingGroupId = $mutualStudents->pluck('grouping_request_id')->filter()->first();
        if ($existingGroupId) {
            $groupRequest = GroupingRequest::where('id', $existingGroupId)
                ->where('status', 'pending')
                ->first();
        }

        if (!$groupRequest && $newStudent->grouping_request_id) {
            $groupRequest = GroupingRequest::where('id', $newStudent->grouping_request_id)
                ->where('status', 'pending')
                ->first();
        }

        if (!$groupRequest) {
            $groupCode = GroupingRequest::generateCode();
            $groupName = 'Mutual Request: ' . $newStudent->nama_lengkap . ' dan kawan2';

            $groupRequest = GroupingRequest::create([
                'request_code' => $groupCode,
                'group_name' => $groupName,
                'tahun_ajaran_id' => $newStudent->tahun_ajaran_id,
                'status' => 'pending',
                'notes' => 'Mutual request terdeteksi otomatis oleh sistem',
                'created_by' => Auth::id(),
            ]);
        }

        $studentIds = $mutualStudents->pluck('id')->push($newStudent->id)->unique()->values();

        CalonSiswa::whereIn('id', $studentIds)->update([
            'grouping_request_id' => $groupRequest->id,
            'grouping_priority' => 'high',
            'requested_with_names' => null,
        ]);

        if (!str_contains($groupRequest->notes, $newStudent->nama_lengkap)) {
            $groupRequest->notes = trim($groupRequest->notes . ' | ' . $newStudent->nama_lengkap);
            $groupRequest->save();
        }

        Log::info('Mutual request detected', [
            'group_id' => $groupRequest->id,
            'students' => $studentIds,
        ]);
    }
}
