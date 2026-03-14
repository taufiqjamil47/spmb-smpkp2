<?php

namespace App\Http\Controllers;

use App\Models\CalonSiswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\CalonSiswaExport;
use App\Exports\CalonSiswaCsvExport;
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

        return view('pendaftaran.index', compact('pendaftar', 'tahunAjaran'));
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
            'nama_lengkap' => 'required|min:3',
            'nisn' => 'required|unique:calon_siswas,nisn',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required',
            'agama' => 'required',
            'alamat' => 'required',
            'no_hp_siswa' => 'required',
            'sekolah_asal' => 'required',
            'tahun_lulus' => 'required|digits:4',
            'nama_ayah' => 'required',
            'nama_ibu' => 'required',
            'pekerjaan_ortu' => 'required',
            'no_hp_ortu' => 'required',
        ]);

        // Generate nomor peserta otomatis
        $tahun = date('Y');
        $lastSiswa = CalonSiswa::withTrashed() // Include soft deleted records
            ->whereYear('created_at', $tahun)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastSiswa) {
            $lastNumber = intval(substr($lastSiswa->no_peserta, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        $no_peserta = 'PPDB-' . $tahun . '-' . $newNumber;

        // Simpan data
        $calonSiswa = CalonSiswa::create([
            'no_peserta' => $no_peserta,
            'tahun_ajaran_id' => $tahunAjaranAktif->id,
            'nama_lengkap' => $request->nama_lengkap,
            'nisn' => $request->nisn,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'agama' => $request->agama,
            'alamat' => $request->alamat,
            'no_hp_siswa' => $request->no_hp_siswa,
            'sekolah_asal' => $request->sekolah_asal,
            'tahun_lulus' => $request->tahun_lulus,
            'nama_ayah' => $request->nama_ayah,
            'nama_ibu' => $request->nama_ibu,
            'pekerjaan_ortu' => $request->pekerjaan_ortu,
            'no_hp_ortu' => $request->no_hp_ortu,
        ]);

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
        if (auth()->user()->role !== 'admin') {
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
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengembalikan data.'
            ], 403);
        }

        $calonSiswa = CalonSiswa::onlyTrashed()->findOrFail($id);
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
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menghapus data permanen.'
            ], 403);
        }

        $calonSiswa = CalonSiswa::onlyTrashed()->findOrFail($id);

        // Hapus data terkait (dokumen, dll) jika ada
        if ($calonSiswa->dokumen()->count() > 0) {
            // Hapus file fisik
            foreach ($calonSiswa->dokumen as $dokumen) {
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
        if (auth()->user()->role !== 'admin') {
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
        if (auth()->user()->role !== 'admin') {
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
        if (auth()->user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk export data.');
        }

        $tahunAjaranId = $request->tahun;
        $status = $request->status; // 'aktif', 'trash', 'all'

        $fileName = 'data-pendaftar-ppdb';

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
        if (auth()->user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk export data.');
        }

        $tahunAjaranId = $request->tahun;
        $status = $request->status;

        $fileName = 'data-pendaftar-ppdb';

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
}
