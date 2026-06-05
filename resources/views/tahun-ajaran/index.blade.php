@extends('layouts.app')

@section('title', 'Kelola Kuota')

@section('content')
    <div class="div mt-8">
        <!-- Header Section with Gradient -->
        <div
            class="mb-8 bg-gradient-to-r from-teal-600 to-cyan-600 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden">
            {{-- <div class="absolute top-0 right-0 opacity-10">
                <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L15 8.5L22 9.5L17 14L18.5 21L12 17.5L5.5 21L7 14L2 9.5L9 8.5L12 2Z" />
                </svg>
            </div> --}}
            <div class="relative z-10">
                <div class="flex justify-between items-center flex-wrap gap-4">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Kelola Tahun Ajaran & Kuota</h1>
                        <p class="text-teal-100">Atur kuota pendaftaran untuk setiap tahun ajaran</p>
                    </div>
                    <a href="{{ route('tahun-ajaran.create') }}"
                        class="bg-white text-teal-600 px-5 py-2.5 rounded-xl hover:bg-gray-100 transition-all font-medium shadow-md hover:shadow-lg flex items-center">
                        <i class="fas fa-plus mr-2"></i>Tambah Tahun Ajaran
                    </a>
                </div>
            </div>
        </div>

        <!-- Ringkasan Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            @php
                $aktif = $tahunAjaran->where('status', 'aktif')->first();
                $totalKuota = $tahunAjaran->sum('kuota');
                $totalTerisi = \App\Models\CalonSiswa::count();
            @endphp

            <!-- Card Tahun Aktif -->
            <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg">
                            <i class="fas fa-calendar-check text-white text-xl"></i>
                        </div>
                        <span
                            class="text-3xl font-bold text-gray-800 truncate max-w-[150px]">{{ $aktif ? $aktif->tahun_ajaran : '-' }}</span>
                    </div>
                    <p class="text-gray-500 text-sm font-medium">Tahun Ajaran Aktif</p>
                    <div class="mt-2 h-1 bg-green-100 rounded-full">
                        <div class="w-full h-full bg-gradient-to-r from-green-500 to-green-600 rounded-full"></div>
                    </div>
                </div>
            </div>

            <!-- Card Total Terisi -->
            <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg">
                            <i class="fas fa-users text-white text-xl"></i>
                        </div>
                        <span class="text-3xl font-bold text-gray-800">{{ number_format($totalTerisi) }}</span>
                    </div>
                    <p class="text-gray-500 text-sm font-medium">Total Terisi</p>
                    <div class="mt-2 h-1 bg-blue-100 rounded-full">
                        <div class="w-3/4 h-full bg-gradient-to-r from-blue-500 to-blue-600 rounded-full"></div>
                    </div>
                </div>
            </div>

            <!-- Card Total Kuota -->
            <div class="group bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg">
                            <i class="fas fa-chart-line text-white text-xl"></i>
                        </div>
                        <span class="text-3xl font-bold text-gray-800">{{ number_format($totalKuota) }}</span>
                    </div>
                    <p class="text-gray-500 text-sm font-medium">Total Kuota</p>
                    <div class="mt-2 h-1 bg-purple-100 rounded-full">
                        <div class="w-2/3 h-full bg-gradient-to-r from-purple-500 to-purple-600 rounded-full"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Data -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Tahun Ajaran</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Kuota</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Terisi</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Sisa</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Progress</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($tahunAjaran as $index => $ta)
                            @php
                                $terisi = $ta->calon_siswa_count;
                                $sisa = $ta->kuota - $terisi;
                                $persentase = $ta->kuota > 0 ? round(($terisi / $ta->kuota) * 100) : 0;

                                if ($persentase >= 100) {
                                    $warna = 'from-red-500 to-red-600';
                                    $bgWarna = 'bg-red-100';
                                    $textWarna = 'text-red-600';
                                } elseif ($persentase >= 75) {
                                    $warna = 'from-yellow-500 to-yellow-600';
                                    $bgWarna = 'bg-yellow-100';
                                    $textWarna = 'text-yellow-600';
                                } else {
                                    $warna = 'from-green-500 to-green-600';
                                    $bgWarna = 'bg-green-100';
                                    $textWarna = 'text-green-600';
                                }
                            @endphp
                            <tr
                                class="{{ $ta->status == 'aktif' ? 'bg-gradient-to-r from-teal-50 to-cyan-50 hover:from-teal-100 hover:to-cyan-100' : 'hover:bg-gray-50' }} transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-gray-700">{{ $index + 1 }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900">{{ $ta->tahun_ajaran }}</div>
                                    @if ($ta->status == 'aktif')
                                        <span class="text-xs text-teal-600"><i class="fas fa-star"></i> Berjalan</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-800">{{ number_format($ta->kuota) }}</td>
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-gray-800">{{ number_format($terisi) }}</span>
                                    <span class="text-xs text-gray-400">/ {{ number_format($ta->kuota) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-bold {{ $textWarna }} {{ $bgWarna }}">
                                        {{ number_format($sisa) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 w-64">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-1 bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-gradient-to-r {{ $warna }} h-2.5 rounded-full transition-all duration-500"
                                                style="width: {{ $persentase }}%">
                                            </div>
                                        </div>
                                        <span
                                            class="text-sm font-semibold text-gray-700 min-w-[45px]">{{ $persentase }}%</span>
                                    </div>
            </div>
            <td class="px-6 py-4">
                @if ($ta->status == 'aktif')
                    <span
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-1"></i>Aktif
                    </span>
                @else
                    <span
                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                        <i class="fas fa-circle mr-1 text-gray-400"></i>Tidak Aktif
                    </span>
                @endif
            </td>
            <td class="px-6 py-4">
                <div class="flex space-x-1.5">
                    <a href="{{ route('tahun-ajaran.edit', $ta->id) }}"
                        class="text-yellow-600 hover:text-white hover:bg-yellow-600 p-2 rounded-lg transition-all"
                        title="Edit Tahun Ajaran">
                        <i class="fas fa-edit"></i>
                    </a>

                    @if ($terisi == 0)
                        <form action="{{ route('tahun-ajaran.destroy', $ta->id) }}" method="POST" class="inline"
                            onsubmit="return confirm('Yakin ingin menghapus tahun ajaran {{ $ta->tahun_ajaran }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="text-red-600 hover:text-white hover:bg-red-600 p-2 rounded-lg transition-all"
                                title="Hapus Tahun Ajaran">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    @else
                        <button class="text-gray-400 bg-gray-100 p-2 rounded-lg cursor-not-allowed" disabled
                            title="Tidak bisa dihapus karena sudah ada pendaftar">
                            <i class="fas fa-trash"></i>
                        </button>
                    @endif

                    <a href="{{ route('pendaftaran.index') }}?tahun={{ $ta->id }}"
                        class="text-green-600 hover:text-white hover:bg-green-600 p-2 rounded-lg transition-all"
                        title="Lihat Pendaftar">
                        <i class="fas fa-users"></i>
                    </a>
                </div>
            </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="px-6 py-16 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <div
                            class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-calendar-times text-4xl text-gray-400"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Data Tahun Ajaran</h3>
                        <p class="text-gray-500 mb-6">Silakan tambah tahun ajaran baru untuk memulai pendaftaran</p>
                        <a href="{{ route('tahun-ajaran.create') }}"
                            class="inline-flex items-center bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-3 rounded-xl hover:shadow-lg transition-all">
                            <i class="fas fa-plus mr-2"></i>Tambah Tahun Ajaran
                        </a>
                    </div>
                </td>
            </tr>
            @endforelse
            </tbody>
            </table>
        </div>
    </div>

    <!-- Informasi Tambahan dengan Desain Premium -->
    <div class="mt-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200 p-5">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <div
                    class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-info-circle text-white"></i>
                </div>
            </div>
            <div class="ml-4 flex-1">
                <h4 class="font-bold text-blue-800 text-lg">Informasi Penting</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-3">
                    <div class="flex items-start">
                        <i class="fas fa-star text-yellow-600 mt-0.5 mr-2 text-sm"></i>
                        <span class="text-sm text-blue-700">Hanya satu tahun ajaran yang dapat berstatus
                            <strong>Aktif</strong></span>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-plus-circle text-green-600 mt-0.5 mr-2 text-sm"></i>
                        <span class="text-sm text-blue-700">Tahun ajaran aktif akan digunakan untuk pendaftaran baru</span>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-orange-600 mt-0.5 mr-2 text-sm"></i>
                        <span class="text-sm text-blue-700">Tahun ajaran yang sudah memiliki pendaftar <strong>tidak dapat
                                dihapus</strong></span>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-chart-simple text-purple-600 mt-0.5 mr-2 text-sm"></i>
                        <span class="text-sm text-blue-700">Progress bar menunjukkan persentase keterisian kuota</span>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-bell text-red-600 mt-0.5 mr-2 text-sm"></i>
                        <span class="text-sm text-blue-700">Jika sisa kuota kurang dari 10, akan ditampilkan peringatan
                            merah</span>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-chart-line text-teal-600 mt-0.5 mr-2 text-sm"></i>
                        <span class="text-sm text-blue-700">Progress bar berwarna hijau (aman), kuning (hampir penuh),
                            merah (penuh)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
