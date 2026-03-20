@extends('layouts.app')

@section('title', 'Statistik PPDB')

@section('content')
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold">Statistik PPDB</h1>
                <p class="text-gray-600">Analisis data pendaftaran siswa</p>
            </div>
            <div class="flex space-x-2">
                <form action="{{ route('statistik.index') }}" method="GET" class="flex space-x-2">
                    <select name="tahun" class="border rounded px-3 py-2">
                        <option value="">Semua Tahun</option>
                        @foreach ($tahunAjaran as $ta)
                            <option value="{{ $ta->id }}" {{ $selectedTahun == $ta->id ? 'selected' : '' }}>
                                {{ $ta->tahun_ajaran }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                </form>
                <a href="#" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    <i class="fas fa-download mr-2"></i>Export
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Total Pendaftar</p>
                    <p class="text-3xl font-bold">{{ number_format($summary['total_pendaftar']) }}</p>
                </div>
                <i class="fas fa-users text-4xl opacity-50"></i>
            </div>
            <div class="mt-2 text-sm text-blue-100">
                <i class="fas fa-arrow-up mr-1"></i> {{ $summary['rata_rata_per_tahun'] }} per tahun
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Aktif</p>
                    <p class="text-3xl font-bold">{{ number_format($summary['total_pendaftar'] - $summary['total_trash']) }}
                    </p>
                </div>
                <i class="fas fa-check-circle text-4xl opacity-50"></i>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg shadow p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm">Di Trash</p>
                    <p class="text-3xl font-bold">{{ number_format($summary['total_trash']) }}</p>
                </div>
                <i class="fas fa-trash-alt text-4xl opacity-50"></i>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Tahun Ajaran</p>
                    <p class="text-3xl font-bold">{{ $summary['total_tahun_ajaran'] }}</p>
                </div>
                <i class="fas fa-calendar text-4xl opacity-50"></i>
            </div>
        </div>
    </div>

    <!-- Statistik Per Tahun Ajaran -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-bold mb-4">Statistik per Tahun Ajaran</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-2 text-left">Tahun Ajaran</th>
                        <th class="px-4 py-2 text-left">Kuota</th>
                        <th class="px-4 py-2 text-left">Terisi</th>
                        <th class="px-4 py-2 text-left">Persentase</th>
                        <th class="px-4 py-2 text-left">Progress</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($statPerTahun as $stat)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $stat['tahun'] }}</td>
                            <td class="px-4 py-2">{{ number_format($stat['kuota']) }}</td>
                            <td class="px-4 py-2">{{ number_format($stat['total']) }}</td>
                            <td class="px-4 py-2">{{ $stat['persentase'] }}%</td>
                            <td class="px-4 py-2 w-48">
                                @php
                                    $warna =
                                        $stat['persentase'] >= 100
                                            ? 'bg-red-500'
                                            : ($stat['persentase'] >= 75
                                                ? 'bg-yellow-500'
                                                : 'bg-green-500');
                                @endphp
                                <div class="flex items-center">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="{{ $warna }} h-2 rounded-full"
                                            style="width: {{ $stat['persentase'] }}%"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Grid Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Statistik Jenis Kelamin -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Jenis Kelamin</h2>
            <div class="space-y-2">
                @foreach ($statJk as $jk => $total)
                    <div class="flex justify-between items-center">
                        <span>{{ $jk }}</span>
                        <span class="font-bold">{{ number_format($total) }}
                            ({{ round(($total / $summary['total_pendaftar']) * 100) }}%)
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Statistik Agama -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Agama</h2>
            <div class="max-h-40 overflow-y-auto">
                @foreach ($statAgama as $agama)
                    <div class="flex justify-between items-center py-1">
                        <span>{{ $agama->agama }}</span>
                        <span class="font-bold">{{ number_format($agama->total) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Statistik Pekerjaan & Pendidikan -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Pekerjaan Ayah -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Pekerjaan Ayah</h2>
            <div class="max-h-60 overflow-y-auto">
                @foreach ($statPekerjaanAyah as $pekerjaan)
                    <div class="flex justify-between items-center py-1 border-b last:border-0">
                        <span>{{ $pekerjaan->pekerjaan_ayah ?: 'Tidak diisi' }}</span>
                        <span class="font-bold">{{ number_format($pekerjaan->total) }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Pendidikan Ibu -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Pendidikan Ibu</h2>
            <div class="max-h-60 overflow-y-auto">
                @foreach ($statPendidikanIbu as $pendidikan)
                    <div class="flex justify-between items-center py-1 border-b last:border-0">
                        <span>{{ $pendidikan->pendidikan_ibu ?: 'Tidak diisi' }}</span>
                        <span class="font-bold">{{ number_format($pendidikan->total) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Statistik Asal Sekolah & Ukuran Baju -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Top 10 Asal Sekolah -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Top 10 Asal Sekolah</h2>
            <div class="max-h-60 overflow-y-auto">
                @foreach ($statSekolah as $sekolah)
                    <div class="flex justify-between items-center py-1 border-b last:border-0">
                        <span>{{ $sekolah->sekolah_asal ?: 'Tidak diisi' }}</span>
                        <span class="font-bold">{{ number_format($sekolah->total) }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Distribusi Ukuran Baju per Jenis Kelamin -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Distribusi Ukuran Baju per Jenis Kelamin</h2>

            @php
                // Group data by ukuran baju
                $groupedData = [];
                foreach ($statUkuranBaju as $item) {
                    $ukuran = $item->ukuran_baju;
                    $jk = $item->jenis_kelamin;
                    $total = $item->total;

                    if (!isset($groupedData[$ukuran])) {
                        $groupedData[$ukuran] = ['Laki-laki' => 0, 'Perempuan' => 0];
                    }

                    if ($jk == 'L') {
                        $groupedData[$ukuran]['Laki-laki'] = $total;
                    } else {
                        $groupedData[$ukuran]['Perempuan'] = $total;
                    }
                }
            @endphp

            <!-- Tabel Distribusi Ukuran Baju -->
            <div class="overflow-x-auto mb-6">
                <table class="min-w-full border">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-2 text-left border">Ukuran Baju</th>
                            <th class="px-4 py-2 text-left border">Laki-laki</th>
                            <th class="px-4 py-2 text-left border">Perempuan</th>
                            <th class="px-4 py-2 text-left border bg-blue-50">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($groupedData as $ukuran => $data)
                            <tr class="border-t">
                                <td class="px-4 py-2 border font-bold">{{ $ukuran }}</td>
                                <td class="px-4 py-2 border">
                                    <span class="inline-flex items-center">
                                        <span class="w-16">{{ $data['Laki-laki'] }}</span>
                                        @if ($data['Laki-laki'] > 0)
                                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full ml-2">
                                                {{ round(($data['Laki-laki'] / array_sum($data)) * 100) }}%
                                            </span>
                                        @endif
                                    </span>
                                </td>
                                <td class="px-4 py-2 border">
                                    <span class="inline-flex items-center">
                                        <span class="w-16">{{ $data['Perempuan'] }}</span>
                                        @if ($data['Perempuan'] > 0)
                                            <span class="text-xs bg-pink-100 text-pink-800 px-2 py-1 rounded-full ml-2">
                                                {{ round(($data['Perempuan'] / array_sum($data)) * 100) }}%
                                            </span>
                                        @endif
                                    </span>
                                </td>
                                <td class="px-4 py-2 border bg-blue-50 font-bold">
                                    {{ array_sum($data) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-100 font-bold">
                        @php
                            $totalLaki = collect($groupedData)->sum('Laki-laki');
                            $totalPerempuan = collect($groupedData)->sum('Perempuan');
                            $totalSemua = $totalLaki + $totalPerempuan;
                        @endphp
                        <tr>
                            <td class="px-4 py-2 border">TOTAL</td>
                            <td class="px-4 py-2 border">{{ $totalLaki }}</td>
                            <td class="px-4 py-2 border">{{ $totalPerempuan }}</td>
                            <td class="px-4 py-2 border">{{ $totalSemua }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Grid Cards sederhana (opsional, jika masih ingin menampilkan versi sederhana) -->
            <h3 class="text-lg font-semibold mb-3">Ringkasan per Ukuran</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
                @foreach ($statUkuranBajuTotal as $ukuran)
                    <div
                        class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-4 text-center border border-blue-100">
                        <div class="text-2xl font-bold text-blue-600">{{ $ukuran->ukuran_baju }}</div>
                        <div class="text-sm text-gray-600 mt-1">Total</div>
                        <div class="text-xl font-bold">{{ $ukuran->total }}</div>
                        <div class="flex justify-center space-x-2 mt-2 text-xs">
                            @php
                                $laki = $groupedData[$ukuran->ukuran_baju]['Laki-laki'] ?? 0;
                                $perempuan = $groupedData[$ukuran->ukuran_baju]['Perempuan'] ?? 0;
                            @endphp
                            <span class="text-blue-600">L: {{ $laki }}</span>
                            <span class="text-pink-600">P: {{ $perempuan }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Sebaran Alamat -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold mb-4">Top 10 Alamat dengan Pendaftar Terbanyak</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($statAlamat as $alamat)
                <div class="flex justify-between items-center py-1 border-b">
                    <div class="flex-1">
                        @if (isset($alamat->alamat) && $alamat->alamat)
                            <span class="font-medium">{{ $alamat->alamat }}</span>
                            @if (isset($alamat->desa) && $alamat->desa)
                                <span class="text-sm text-gray-600 block">
                                    RT {{ $alamat->rt }}/RW {{ $alamat->rw }}, {{ $alamat->desa }},
                                    {{ $alamat->kecamatan }}
                                </span>
                            @endif
                        @else
                            <span class="text-gray-400">Alamat tidak lengkap</span>
                        @endif
                    </div>
                    <span class="font-bold bg-blue-100 text-blue-800 px-3 py-1 rounded-full">
                        {{ number_format($alamat->total) }} siswa
                    </span>
                </div>
            @endforeach
        </div>

        @if ($statAlamat->isEmpty())
            <p class="text-center text-gray-500 py-4">Tidak ada data alamat</p>
        @endif
    </div>
@endsection
