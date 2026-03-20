<?php

namespace App\Http\Controllers;

use App\Models\CalonSiswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatistikController extends Controller
{
    /**
     * Display statistics dashboard.
     */
    public function index(Request $request)
    {
        $tahunAjaran = TahunAjaran::orderBy('tahun_ajaran', 'desc')->get();
        $selectedTahun = $request->tahun ?: ($tahunAjaran->where('status', 'aktif')->first()?->id ?? null);

        // Data untuk summary cards
        $summary = [
            'total_pendaftar' => CalonSiswa::count(),
            'total_trash' => CalonSiswa::onlyTrashed()->count(),
            'total_tahun_ajaran' => TahunAjaran::count(),
            'rata_rata_per_tahun' => round(CalonSiswa::count() / max(TahunAjaran::count(), 1)),
        ];

        // Statistik per tahun ajaran
        $statPerTahun = TahunAjaran::withCount('calonSiswa')
            ->orderBy('tahun_ajaran')
            ->get()
            ->map(function ($ta) {
                return [
                    'tahun' => $ta->tahun_ajaran,
                    'total' => $ta->calon_siswa_count,
                    'kuota' => $ta->kuota,
                    'persentase' => $ta->kuota > 0 ? round(($ta->calon_siswa_count / $ta->kuota) * 100) : 0
                ];
            });

        // Statistik berdasarkan jenis kelamin
        $statJk = CalonSiswa::select('jenis_kelamin', DB::raw('count(*) as total'))
            ->groupBy('jenis_kelamin')
            ->get()
            ->mapWithKeys(function ($item) {
                $label = $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan';
                return [$label => $item->total];
            });

        // Statistik berdasarkan agama
        $statAgama = CalonSiswa::select('agama', DB::raw('count(*) as total'))
            ->groupBy('agama')
            ->orderBy('total', 'desc')
            ->get();

        // Statistik berdasarkan pekerjaan ayah
        $statPekerjaanAyah = CalonSiswa::select('pekerjaan_ayah', DB::raw('count(*) as total'))
            ->whereNotNull('pekerjaan_ayah')
            ->groupBy('pekerjaan_ayah')
            ->orderBy('total', 'desc')
            ->get();

        // Statistik berdasarkan pendidikan ibu
        $statPendidikanIbu = CalonSiswa::select('pendidikan_ibu', DB::raw('count(*) as total'))
            ->whereNotNull('pendidikan_ibu')
            ->groupBy('pendidikan_ibu')
            ->orderBy('total', 'desc')
            ->get();

        // Statistik berdasarkan asal sekolah
        $statSekolah = CalonSiswa::select('sekolah_asal', DB::raw('count(*) as total'))
            ->groupBy('sekolah_asal')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        // Sebaran berdasarkan Alamat (hanya alamat)
        $statAlamat = CalonSiswa::select('alamat', DB::raw('count(*) as total'))
            ->whereNotNull('alamat')
            ->where('alamat', '!=', '')
            ->groupBy('alamat')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        // Statistik berdasarkan ukuran baju per jenis kelamin
        $statUkuranBaju = CalonSiswa::select(
            'ukuran_baju',
            'jenis_kelamin',
            DB::raw('count(*) as total')
        )
            ->whereNotNull('ukuran_baju')
            ->whereNotNull('jenis_kelamin')
            ->groupBy('ukuran_baju', 'jenis_kelamin')
            ->orderBy('ukuran_baju')
            ->orderBy('jenis_kelamin')
            ->get();

        // Untuk keperluan total per ukuran (jika masih diperlukan)
        $statUkuranBajuTotal = CalonSiswa::select('ukuran_baju', DB::raw('count(*) as total'))
            ->whereNotNull('ukuran_baju')
            ->groupBy('ukuran_baju')
            ->orderBy('ukuran_baju')
            ->get();

        // Data untuk chart pendaftaran per bulan (tahun berjalan)
        $bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        $pendaftarPerBulan = [];

        foreach (range(1, 12) as $bulanNum) {
            $count = CalonSiswa::whereYear('created_at', date('Y'))
                ->whereMonth('created_at', $bulanNum)
                ->count();
            $pendaftarPerBulan[] = $count;
        }

        // Data jika filter tahun dipilih
        if ($selectedTahun) {
            $tahunObj = TahunAjaran::find($selectedTahun);
            if ($tahunObj) {
                $pendaftarTahun = CalonSiswa::where('tahun_ajaran_id', $selectedTahun)->get();

                $statJkTahun = $pendaftarTahun->groupBy('jenis_kelamin')
                    ->map(function ($item) {
                        return $item->count();
                    });

                $statAgamaTahun = $pendaftarTahun->groupBy('agama')
                    ->map(function ($item) {
                        return $item->count();
                    });
            }
        }

        return view('statistik.index', compact(
            'tahunAjaran',
            'selectedTahun',
            'summary',
            'statPerTahun',
            'statJk',
            'statAgama',
            'statPekerjaanAyah',
            'statPendidikanIbu',
            'statSekolah',
            'statAlamat',
            'statUkuranBaju',
            'statUkuranBajuTotal',
            'bulan',
            'pendaftarPerBulan'
        ));
    }

    /**
     * Export statistik ke Excel.
     */
    public function export(Request $request)
    {
        // Implementasi export statistik
    }
}
