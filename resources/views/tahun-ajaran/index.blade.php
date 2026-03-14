@extends('layouts.app')

@section('title', 'Kelola Kuota')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold">Kelola Tahun Ajaran & Kuota</h1>
            <p class="text-gray-600">Atur kuota pendaftaran untuk setiap tahun ajaran</p>
        </div>
        <a href="{{ route('tahun-ajaran.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            <i class="fas fa-plus mr-2"></i>Tambah Tahun Ajaran
        </a>
    </div>

    <!-- Ringkasan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        @php
            $aktif = $tahunAjaran->where('status', 'aktif')->first();
            $totalKuota = $tahunAjaran->sum('kuota');
            $totalTerisi = \App\Models\CalonSiswa::count();
        @endphp

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-500 rounded-full text-white mr-4">
                    <i class="fas fa-calendar-check text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Tahun Aktif</p>
                    <p class="text-2xl font-bold">{{ $aktif ? $aktif->tahun_ajaran : '-' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-500 rounded-full text-white mr-4">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Terisi</p>
                    <p class="text-2xl font-bold">{{ $totalTerisi }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-500 rounded-full text-white mr-4">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Kuota</p>
                    <p class="text-2xl font-bold">{{ $totalKuota }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun Ajaran</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kuota</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Terisi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sisa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Progress</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($tahunAjaran as $index => $ta)
                    @php
                        $terisi = $ta->calonSiswa()->count();
                        $sisa = $ta->kuota - $terisi;
                        $persentase = $ta->kuota > 0 ? round(($terisi / $ta->kuota) * 100) : 0;

                        // Tentukan warna progress bar
                        if ($persentase >= 100) {
                            $warna = 'bg-red-500';
                        } elseif ($persentase >= 75) {
                            $warna = 'bg-yellow-500';
                        } else {
                            $warna = 'bg-green-500';
                        }
                    @endphp
                    <tr class="{{ $ta->status == 'aktif' ? 'bg-blue-50' : '' }}">
                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-medium">{{ $ta->tahun_ajaran }}</td>
                        <td class="px-6 py-4">{{ number_format($ta->kuota) }}</td>
                        <td class="px-6 py-4">{{ number_format($terisi) }}</td>
                        <td class="px-6 py-4 font-bold {{ $sisa < 10 ? 'text-red-600' : 'text-green-600' }}">
                            {{ number_format($sisa) }}
                        </td>
                        <td class="px-6 py-4 w-48">
                            <div class="flex items-center">
                                <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                    <div class="{{ $warna }} h-2 rounded-full" style="width: {{ $persentase }}%">
                                    </div>
                                </div>
                                <span class="text-xs text-gray-600">{{ $persentase }}%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if ($ta->status == 'aktif')
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-semibold">
                                    <i class="fas fa-check-circle mr-1"></i>Aktif
                                </span>
                            @else
                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">
                                    Tidak Aktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('tahun-ajaran.edit', $ta->id) }}"
                                    class="text-blue-600 hover:text-blue-900 bg-blue-100 p-2 rounded" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                @if ($terisi == 0)
                                    <form action="{{ route('tahun-ajaran.destroy', $ta->id) }}" method="POST"
                                        class="inline"
                                        onsubmit="return confirm('Yakin ingin menghapus tahun ajaran {{ $ta->tahun_ajaran }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-900 bg-red-100 p-2 rounded" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <button class="text-gray-400 bg-gray-100 p-2 rounded cursor-not-allowed" disabled
                                        title="Tidak bisa dihapus karena sudah ada pendaftar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif

                                <a href="{{ route('pendaftaran.index') }}?tahun={{ $ta->id }}"
                                    class="text-green-600 hover:text-green-900 bg-green-100 p-2 rounded"
                                    title="Lihat Pendaftar">
                                    <i class="fas fa-users"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-calendar-times text-5xl mb-4"></i>
                                <p class="text-lg">Belum ada data tahun ajaran</p>
                                <p class="text-sm">Silakan tambah tahun ajaran baru untuk memulai pendaftaran</p>
                                <a href="{{ route('tahun-ajaran.create') }}"
                                    class="inline-block mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                    <i class="fas fa-plus mr-2"></i>Tambah Sekarang
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Informasi Tambahan -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
            <div>
                <h4 class="font-semibold text-blue-800">Informasi Penting:</h4>
                <ul class="text-sm text-blue-700 list-disc list-inside mt-2">
                    <li>Hanya satu tahun ajaran yang dapat berstatus <span class="font-bold">Aktif</span></li>
                    <li>Tahun ajaran aktif akan digunakan untuk pendaftaran baru</li>
                    <li>Tahun ajaran yang sudah memiliki pendaftar <span class="font-bold">tidak dapat dihapus</span></li>
                    <li>Progress bar menunjukkan persentase keterisian kuota</li>
                    <li>Jika sisa kuota kurang dari 10, angka akan berwarna merah</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
