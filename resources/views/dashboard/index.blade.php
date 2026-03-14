@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-bold">Dashboard PPDB</h1>
        <p class="text-gray-600">Selamat datang, {{ auth()->user()->name }}! ({{ ucfirst(auth()->user()->role) }})</p>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-500 rounded-full text-white mr-4">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Pendaftar</p>
                    <p class="text-2xl font-bold">{{ number_format($totalPendaftar) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-500 rounded-full text-white mr-4">
                    <i class="fas fa-calendar-check text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Tahun Aktif</p>
                    <p class="text-2xl font-bold">{{ $tahunAjaranAktif ? $tahunAjaranAktif->tahun_ajaran : '-' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-500 rounded-full text-white mr-4">
                    <i class="fas fa-layer-group text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Tahun Ajaran</p>
                    <p class="text-2xl font-bold">{{ $totalTahunAjaran }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-500 rounded-full text-white mr-4">
                    <i class="fas fa-trophy text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Rata-rata/Tahun</p>
                    <p class="text-2xl font-bold">
                        {{ $totalTahunAjaran > 0 ? round($totalPendaftar / $totalTahunAjaran) : 0 }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    @if ($tahunAjaranAktif)
        @php
            $jumlahPendaftar = $tahunAjaranAktif->calonSiswa()->count();
            $sisaKuota = $tahunAjaranAktif->kuota - $jumlahPendaftar;
            $persentase = round(($jumlahPendaftar / $tahunAjaranAktif->kuota) * 100);

            if ($persentase >= 100) {
                $warnaProgress = 'bg-red-500';
                $statusText = 'Kuota Penuh';
            } elseif ($persentase >= 75) {
                $warnaProgress = 'bg-yellow-500';
                $statusText = 'Hampir Penuh';
            } else {
                $warnaProgress = 'bg-green-500';
                $statusText = 'Tersedia';
            }
        @endphp

        <!-- Progress Kuota -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Progress Kuota {{ $tahunAjaranAktif->tahun_ajaran }}</h2>
                <span
                    class="px-3 py-1 bg-{{ $warnaProgress == 'bg-red-500' ? 'red' : ($warnaProgress == 'bg-yellow-500' ? 'yellow' : 'green') }}-100 text-{{ $warnaProgress == 'bg-red-500' ? 'red' : ($warnaProgress == 'bg-yellow-500' ? 'yellow' : 'green') }}-800 rounded-full text-sm font-semibold">
                    {{ $statusText }}
                </span>
            </div>

            <div class="flex items-center mb-2">
                <div class="flex-1">
                    <div class="w-full bg-gray-200 rounded-full h-4">
                        <div class="{{ $warnaProgress }} h-4 rounded-full transition-all duration-500"
                            style="width: {{ $persentase }}%"></div>
                    </div>
                </div>
                <span class="ml-4 font-bold text-lg">{{ $persentase }}%</span>
            </div>

            <div class="flex justify-between text-sm text-gray-600">
                <span>{{ number_format($jumlahPendaftar) }} dari {{ number_format($tahunAjaranAktif->kuota) }}
                    siswa</span>
                <span class="font-bold {{ $sisaKuota < 10 ? 'text-red-600' : 'text-green-600' }}">
                    Sisa: {{ number_format($sisaKuota) }}
                </span>
            </div>
        </div>

        <!-- Grafik Pendaftar per Bulan -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Grafik Pendaftar {{ $tahunAjaranAktif->tahun_ajaran }}</h2>
                <div class="h-64 flex items-end space-x-2">
                    @foreach ($pendaftarPerBulan as $bulan => $jumlah)
                        @php
                            $tinggi = $jumlah * 8; // 1 siswa = 8px tinggi
                            $warna = $jumlah > 0 ? 'bg-blue-500' : 'bg-gray-200';
                        @endphp
                        <div class="flex-1 flex flex-col items-center group">
                            <div class="relative w-full">
                                <div class="{{ $warna }} rounded-t transition-all duration-300 group-hover:bg-blue-600"
                                    style="height: {{ $tinggi > 0 ? $tinggi : 4 }}px"></div>
                                @if ($jumlah > 0)
                                    <div
                                        class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs rounded py-1 px-2 opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                        {{ $jumlah }} siswa
                                    </div>
                                @endif
                            </div>
                            <span class="text-xs mt-2 text-gray-600">{{ substr($bulan, 0, 3) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Grafik Perbandingan Tahun Ajaran -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Perbandingan 5 Tahun Terakhir</h2>
                <canvas id="chartTahunAjaran" class="h-64"></canvas>
            </div>
        </div>
    @else
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-8">
            <div class="flex">
                <div class="py-1">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                </div>
                <div>
                    <p class="font-bold">Perhatian!</p>
                    <p>Belum ada tahun ajaran yang aktif. Silakan <a href="{{ route('tahun-ajaran.index') }}"
                            class="underline">atur tahun ajaran</a> terlebih dahulu.</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Recent Pendaftar -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Pendaftar Terbaru</h2>
            <a href="{{ route('pendaftaran.index') }}" class="text-blue-500 hover:text-blue-700 text-sm">
                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Peserta</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun Ajaran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl Daftar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($recentPendaftar as $siswa)
                        <tr>
                            <td class="px-6 py-4 font-mono text-sm">{{ $siswa->no_peserta }}</td>
                            <td class="px-6 py-4">{{ $siswa->nama_lengkap }}</td>
                            <td class="px-6 py-4">{{ $siswa->tahunAjaran->tahun_ajaran }}</td>
                            <td class="px-6 py-4">{{ $siswa->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('pendaftaran.show', $siswa->id) }}"
                                    class="text-blue-600 hover:text-blue-900 mr-2">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('pendaftaran.cetak', $siswa->id) }}" target="_blank"
                                    class="text-green-600 hover:text-green-900">
                                    <i class="fas fa-print"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                Belum ada data pendaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Chart untuk perbandingan tahun ajaran
            const ctx = document.getElementById('chartTahunAjaran').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Jumlah Pendaftar',
                        data: {!! json_encode($chartData) !!},
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        </script>
    @endpush
@endsection
