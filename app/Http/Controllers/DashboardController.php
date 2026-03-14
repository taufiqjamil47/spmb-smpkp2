<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TahunAjaran;
use App\Models\CalonSiswa;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil tahun ajaran aktif
        $tahunAjaranAktif = TahunAjaran::where('status', 'aktif')->first();

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

        // Data pendaftar per bulan (untuk tahun aktif)
        $pendaftarPerBulan = [];
        if ($tahunAjaranAktif) {
            for ($i = 1; $i <= 12; $i++) {
                $bulan = date('F', mktime(0, 0, 0, $i, 1));
                $jumlah = CalonSiswa::where('tahun_ajaran_id', $tahunAjaranAktif->id)
                    ->whereMonth('created_at', $i)
                    ->count();
                $pendaftarPerBulan[$bulan] = $jumlah;
            }
        }

        // Data untuk tabel recent pendaftar
        $recentPendaftar = CalonSiswa::with('tahunAjaran')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'tahunAjaranAktif',
            'totalPendaftar',
            'totalTahunAjaran',
            'chartLabels',
            'chartData',
            'pendaftarPerBulan',
            'recentPendaftar'
        ));
    }
}
