@extends('layouts.app')

@section('title', 'Statistik SPMB')

@section('content')
    <div class="div mt-8">
        <!-- Header Section with Gradient -->
        <div
            class="mb-8 bg-gradient-to-r from-blue-600 to-cyan-600 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden">
            {{-- <div class="absolute top-0 right-0 opacity-10">
                <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L15 8.5L22 9.5L17 14L18.5 21L12 17.5L5.5 21L7 14L2 9.5L9 8.5L12 2Z" />
                </svg>
            </div> --}}
            <div class="relative z-10">
                <div class="flex justify-between items-center flex-wrap gap-4">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Statistik SPMB</h1>
                        <p class="text-blue-100">Analisis data pendaftaran siswa</p>
                    </div>
                    <div class="flex space-x-2">
                        <form action="{{ route('statistik.index') }}" method="GET" class="flex space-x-2">
                            <div class="relative">
                                <i
                                    class="fas fa-calendar-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <select name="tahun"
                                    class="pl-10 pr-8 py-2 border border-gray-200 rounded-xl focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all appearance-none bg-white text-gray-900 cursor-pointer">
                                    <option value="">Semua Tahun</option>
                                    @foreach ($tahunAjaran as $ta)
                                        <option value="{{ $ta->id }}"
                                            {{ $selectedTahun == $ta->id ? 'selected' : '' }}>
                                            {{ $ta->tahun_ajaran }}
                                        </option>
                                    @endforeach
                                </select>
                                <i
                                    class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                            </div>
                            <button type="submit"
                                class="bg-white text-blue-600 px-5 py-2 rounded-xl hover:bg-gray-100 transition-all font-medium shadow-md">
                                <i class="fas fa-filter mr-2"></i>Filter
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div
                class="group bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 text-white relative overflow-hidden">
                <div class="absolute -right-4 -top-4 opacity-10 group-hover:opacity-20 transition">
                    <i class="fas fa-users text-7xl"></i>
                </div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-blue-100 text-sm font-medium">Total Pendaftar</p>
                        <i class="fas fa-users text-2xl opacity-70"></i>
                    </div>
                    <p class="text-3xl font-bold">{{ number_format($summary['total_pendaftar']) }}</p>
                    <div class="mt-3 text-xs text-blue-100">
                        <i class="fas fa-chart-line mr-1"></i> Rata-rata: {{ $summary['rata_rata_per_tahun'] }} per tahun
                    </div>
                </div>
            </div>

            <div
                class="group bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 text-white relative overflow-hidden">
                <div class="absolute -right-4 -top-4 opacity-10 group-hover:opacity-20 transition">
                    <i class="fas fa-check-circle text-7xl"></i>
                </div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-green-100 text-sm font-medium">Data Aktif</p>
                        <i class="fas fa-check-circle text-2xl opacity-70"></i>
                    </div>
                    <p class="text-3xl font-bold">{{ number_format($summary['total_pendaftar'] - $summary['total_trash']) }}
                    </p>
                </div>
            </div>

            <div
                class="group bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 text-white relative overflow-hidden">
                <div class="absolute -right-4 -top-4 opacity-10 group-hover:opacity-20 transition">
                    <i class="fas fa-trash-alt text-7xl"></i>
                </div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-orange-100 text-sm font-medium">Di Trash</p>
                        <i class="fas fa-trash-alt text-2xl opacity-70"></i>
                    </div>
                    <p class="text-3xl font-bold">{{ number_format($summary['total_trash']) }}</p>
                </div>
            </div>

            <div
                class="group bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 text-white relative overflow-hidden">
                <div class="absolute -right-4 -top-4 opacity-10 group-hover:opacity-20 transition">
                    <i class="fas fa-calendar text-7xl"></i>
                </div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-purple-100 text-sm font-medium">Tahun Ajaran</p>
                        <i class="fas fa-calendar text-2xl opacity-70"></i>
                    </div>
                    <p class="text-3xl font-bold">{{ $summary['total_tahun_ajaran'] }}</p>
                </div>
            </div>
        </div>

        <!-- Statistik Per Tahun Ajaran -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-chart-bar text-blue-500 mr-2"></i>
                    Statistik per Tahun Ajaran
                </h2>
            </div>
            <div class="overflow-x-auto p-6">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Tahun Ajaran</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Kuota</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Terisi</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Persentase</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Progress</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($statPerTahun as $stat)
                            @php
                                $warnaProgress =
                                    $stat['persentase'] >= 100
                                        ? 'from-red-500 to-red-600'
                                        : ($stat['persentase'] >= 75
                                            ? 'from-yellow-500 to-yellow-600'
                                            : 'from-green-500 to-green-600');
                                $badgeWarna =
                                    $stat['persentase'] >= 100
                                        ? 'bg-red-100 text-red-700'
                                        : ($stat['persentase'] >= 75
                                            ? 'bg-yellow-100 text-yellow-700'
                                            : 'bg-green-100 text-green-700');
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 font-semibold text-gray-800">{{ $stat['tahun'] }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ number_format($stat['kuota']) }}</td>
                                <td class="px-4 py-3">
                                    <span class="font-semibold text-gray-800">{{ number_format($stat['total']) }}</span>
                                    <span class="text-xs text-gray-400">/ {{ number_format($stat['kuota']) }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $badgeWarna }}">
                                        {{ $stat['persentase'] }}%
                                    </span>
                                </td>
                                <td class="px-4 py-3 w-64">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-1 bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-gradient-to-r {{ $warnaProgress }} h-2.5 rounded-full transition-all duration-500"
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
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Statistik Jenis Kelamin -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-pink-50 to-rose-50 px-6 py-4 border-b border-pink-100">
                    <h2 class="text-lg font-bold text-gray-800">
                        <i class="fas fa-venus-mars text-pink-500 mr-2"></i>
                        Jenis Kelamin
                    </h2>
                </div>
                <div class="p-6 space-y-3">
                    @foreach ($statJk as $jk => $total)
                        @php
                            $persen =
                                $summary['total_pendaftar'] > 0
                                    ? round(($total / $summary['total_pendaftar']) * 100)
                                    : 0;
                            $icon = $jk == 'Laki-laki' ? 'fa-mars' : 'fa-venus';
                            $color = $jk == 'Laki-laki' ? 'text-blue-600' : 'text-pink-600';
                            $bgColor = $jk == 'Laki-laki' ? 'bg-blue-100' : 'bg-pink-100';
                        @endphp
                        <div class="group">
                            <div class="flex justify-between items-center mb-2">
                                <div class="flex items-center space-x-2">
                                    <i class="fas {{ $icon }} {{ $color }}"></i>
                                    <span class="font-medium text-gray-700">{{ $jk }}</span>
                                </div>
                                <span class="font-bold text-gray-800">{{ number_format($total) }}
                                    <span class="text-sm text-gray-500">({{ $persen }}%)</span>
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r {{ $jk == 'Laki-laki' ? 'from-blue-500 to-blue-600' : 'from-pink-500 to-pink-600' }} h-2 rounded-full transition-all duration-500"
                                    style="width: {{ $persen }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Statistik Agama -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-purple-50 to-indigo-50 px-6 py-4 border-b border-purple-100">
                    <h2 class="text-lg font-bold text-gray-800">
                        <i class="fas fa-pray text-purple-500 mr-2"></i>
                        Agama
                    </h2>
                </div>
                <div class="p-6 max-h-80 overflow-y-auto space-y-2">
                    @foreach ($statAgama as $agama)
                        @php
                            $persen =
                                $summary['total_pendaftar'] > 0
                                    ? round(($agama->total / $summary['total_pendaftar']) * 100)
                                    : 0;
                        @endphp
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0">
                            <span class="text-gray-700">{{ $agama->agama ?: 'Tidak diisi' }}</span>
                            <div class="flex items-center space-x-3">
                                <span class="font-semibold text-gray-800">{{ number_format($agama->total) }}</span>
                                <span class="text-xs text-gray-400 w-10">{{ $persen }}%</span>
                                <div class="w-24 bg-gray-200 rounded-full h-1.5">
                                    <div class="bg-gradient-to-r from-purple-500 to-indigo-500 h-1.5 rounded-full"
                                        style="width: {{ $persen }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Statistik Pekerjaan & Pendidikan -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Pekerjaan Ayah -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-blue-50 to-cyan-50 px-6 py-4 border-b border-blue-100">
                    <h2 class="text-lg font-bold text-gray-800">
                        <i class="fas fa-briefcase text-blue-500 mr-2"></i>
                        Pekerjaan Ayah
                    </h2>
                </div>
                <div class="p-6 max-h-72 overflow-y-auto">
                    @foreach ($statPekerjaanAyah as $pekerjaan)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0">
                            <span class="text-gray-700">{{ $pekerjaan->pekerjaan_ayah ?: 'Tidak diisi' }}</span>
                            <span class="font-semibold text-gray-800">{{ number_format($pekerjaan->total) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Pendidikan Ibu -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-teal-50 px-6 py-4 border-b border-green-100">
                    <h2 class="text-lg font-bold text-gray-800">
                        <i class="fas fa-graduation-cap text-green-500 mr-2"></i>
                        Pendidikan Ibu
                    </h2>
                </div>
                <div class="p-6 max-h-72 overflow-y-auto">
                    @foreach ($statPendidikanIbu as $pendidikan)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0">
                            <span class="text-gray-700">{{ $pendidikan->pendidikan_ibu ?: 'Tidak diisi' }}</span>
                            <span class="font-semibold text-gray-800">{{ number_format($pendidikan->total) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Statistik Asal Sekolah & Ukuran Baju -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Top 10 Asal Sekolah -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-yellow-50 to-orange-50 px-6 py-4 border-b border-yellow-100">
                    <h2 class="text-lg font-bold text-gray-800">
                        <i class="fas fa-school text-yellow-500 mr-2"></i>
                        Top 10 Asal Sekolah
                    </h2>
                </div>
                <div class="p-6 max-h-72 overflow-y-auto">
                    @foreach ($statSekolah as $sekolah)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0">
                            <span class="text-gray-700">{{ $sekolah->sekolah_asal ?: 'Tidak diisi' }}</span>
                            <span class="font-semibold text-gray-800">{{ number_format($sekolah->total) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Distribusi Ukuran Baju -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-red-50 to-pink-50 px-6 py-4 border-b border-red-100">
                    <h2 class="text-lg font-bold text-gray-800">
                        <i class="fas fa-tshirt text-red-500 mr-2"></i>
                        Distribusi Ukuran Baju
                    </h2>
                </div>
                <div class="p-6">
                    @php
                        $groupedData = [];
                        foreach ($statUkuranBaju as $item) {
                            $ukuran = $item->ukuran_baju ?: 'Tidak diisi';
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

                    <div class="overflow-x-auto mb-6">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-gray-50 rounded-lg">
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Ukuran</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Laki-laki</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600">Perempuan</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 bg-blue-50">Total
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($groupedData as $ukuran => $data)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 font-semibold text-gray-800">{{ $ukuran }}</td>
                                        <td class="px-4 py-2">
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-lg bg-blue-100 text-blue-700 text-sm">
                                                {{ $data['Laki-laki'] }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2">
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-lg bg-pink-100 text-pink-700 text-sm">
                                                {{ $data['Perempuan'] }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 bg-blue-50 font-bold text-gray-800">
                                            {{ array_sum($data) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-100">
                                @php
                                    $totalLaki = collect($groupedData)->sum('Laki-laki');
                                    $totalPerempuan = collect($groupedData)->sum('Perempuan');
                                    $totalSemua = $totalLaki + $totalPerempuan;
                                @endphp
                                <tr class="font-bold">
                                    <td class="px-4 py-2">TOTAL</td>
                                    <td class="px-4 py-2 text-blue-700">{{ $totalLaki }}</td>
                                    <td class="px-4 py-2 text-pink-700">{{ $totalPerempuan }}</td>
                                    <td class="px-4 py-2 bg-blue-100">{{ $totalSemua }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Summary Cards per Ukuran -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 mt-4">
                        @foreach ($statUkuranBajuTotal as $ukuran)
                            @php
                                $laki = $groupedData[$ukuran->ukuran_baju]['Laki-laki'] ?? 0;
                                $perempuan = $groupedData[$ukuran->ukuran_baju]['Perempuan'] ?? 0;
                            @endphp
                            <div
                                class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl p-3 text-center hover:shadow-md transition">
                                <div class="text-xl font-bold text-gray-800">{{ $ukuran->ukuran_baju }}</div>
                                <div class="text-2xl font-bold text-blue-600 mt-1">{{ $ukuran->total }}</div>
                                <div class="flex justify-center space-x-2 mt-2 text-xs">
                                    <span class="text-blue-600"><i class="fas fa-mars"></i> {{ $laki }}</span>
                                    <span class="text-pink-600"><i class="fas fa-venus"></i> {{ $perempuan }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Sebaran Alamat -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-teal-50 to-cyan-50 px-6 py-4 border-b border-teal-100">
                <h2 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-map-marker-alt text-teal-500 mr-2"></i>
                    Top 10 Alamat dengan Pendaftar Terbanyak
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($statAlamat as $alamat)
                        <div
                            class="flex justify-between items-center p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                            <div class="flex-1">
                                @if (isset($alamat->alamat) && $alamat->alamat)
                                    <p class="font-medium text-gray-800">{{ Str::limit($alamat->alamat, 40) }}</p>
                                    @if (isset($alamat->desa) && $alamat->desa)
                                        <p class="text-xs text-gray-500 mt-1">
                                            RT {{ $alamat->rt }}/RW {{ $alamat->rw }}, {{ $alamat->desa }},
                                            {{ $alamat->kecamatan }}
                                        </p>
                                    @endif
                                @else
                                    <p class="text-gray-400">Alamat tidak lengkap</p>
                                @endif
                            </div>
                            <span
                                class="font-bold bg-gradient-to-r from-teal-500 to-cyan-500 text-white px-3 py-1 rounded-full text-sm ml-3">
                                {{ number_format($alamat->total) }} siswa
                            </span>
                        </div>
                    @endforeach
                </div>

                @if ($statAlamat->isEmpty())
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-map-marker-alt text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-gray-500">Tidak ada data alamat</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
