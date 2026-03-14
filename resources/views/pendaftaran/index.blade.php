@extends('layouts.app')

@section('title', 'Data Pendaftar')

@section('content')
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold">Data Calon Siswa</h1>
                <p class="text-gray-600">Total: {{ $pendaftar->total() }} pendaftar</p>
            </div>
            <div class="flex space-x-2">
                @if (auth()->user()->role === 'admin')
                    <!-- Tombol Export Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 flex items-center">
                            <i class="fas fa-download mr-2"></i>
                            Export
                            <i class="fas fa-chevron-down ml-2 text-xs"></i>
                        </button>

                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg z-50 border">

                            <div class="p-3 border-b bg-gray-50 rounded-t-lg">
                                <p class="font-semibold text-sm">Pilih Format</p>
                            </div>

                            <div class="p-2">
                                <!-- Export dengan filter -->
                                <form action="{{ route('export.excel') }}" method="GET" class="mb-2">
                                    <input type="hidden" name="tahun" value="{{ request('tahun') }}">
                                    <input type="hidden" name="status" value="aktif">
                                    <button type="submit"
                                        class="w-full text-left px-3 py-2 hover:bg-gray-100 rounded flex items-center">
                                        <i class="fas fa-file-excel text-green-600 w-5 mr-2"></i>
                                        Excel (.xlsx)
                                    </button>
                                </form>

                                <form action="{{ route('export.csv') }}" method="GET" class="mb-2">
                                    <input type="hidden" name="tahun" value="{{ request('tahun') }}">
                                    <input type="hidden" name="status" value="aktif">
                                    <button type="submit"
                                        class="w-full text-left px-3 py-2 hover:bg-gray-100 rounded flex items-center">
                                        <i class="fas fa-file-csv text-blue-600 w-5 mr-2"></i>
                                        CSV (.csv)
                                    </button>
                                </form>

                                <div class="border-t my-2"></div>

                                <!-- Export dengan status berbeda -->
                                <form action="{{ route('export.excel') }}" method="GET" class="mb-2">
                                    <input type="hidden" name="tahun" value="{{ request('tahun') }}">
                                    <input type="hidden" name="status" value="all">
                                    <button type="submit"
                                        class="w-full text-left px-3 py-2 hover:bg-gray-100 rounded flex items-center text-sm">
                                        <i class="fas fa-database text-purple-600 w-5 mr-2"></i>
                                        Excel - Semua (termasuk trash)
                                    </button>
                                </form>

                                <form action="{{ route('export.excel') }}" method="GET" class="mb-2">
                                    <input type="hidden" name="status" value="trash">
                                    <button type="submit"
                                        class="w-full text-left px-3 py-2 hover:bg-gray-100 rounded flex items-center text-sm">
                                        <i class="fas fa-trash-alt text-red-600 w-5 mr-2"></i>
                                        Excel - Hanya Trash
                                    </button>
                                </form>

                                <div class="border-t my-2"></div>

                                <a href="{{ route('export.template') }}"
                                    class="block w-full text-left px-3 py-2 hover:bg-gray-100 rounded flex items-center text-sm">
                                    <i class="fas fa-file-import text-yellow-600 w-5 mr-2"></i>
                                    Download Template Import
                                </a>
                            </div>

                            <div class="p-2 border-t bg-gray-50 rounded-b-lg">
                                <p class="text-xs text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Data akan difilter berdasarkan pencarian saat ini
                                </p>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('pendaftaran.trash') }}"
                        class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600">
                        <i class="fas fa-trash-alt mr-2"></i>Trash
                        @php
                            $trashCount = \App\Models\CalonSiswa::onlyTrashed()->count();
                        @endphp
                        @if ($trashCount > 0)
                            <span
                                class="ml-1 bg-white text-orange-500 px-2 py-0.5 rounded-full text-xs">{{ $trashCount }}</span>
                        @endif
                    </a>
                @endif
                <a href="{{ route('pendaftaran.create') }}"
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    <i class="fas fa-plus mr-2"></i>Tambah Pendaftar
                </a>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form action="{{ route('pendaftaran.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama, no. peserta, atau NISN..." class="w-full border rounded px-4 py-2">
            </div>
            <div class="w-48">
                <select name="tahun" class="w-full border rounded px-4 py-2">
                    <option value="">Semua Tahun</option>
                    @foreach ($tahunAjaran as $ta)
                        <option value="{{ $ta->id }}" {{ request('tahun') == $ta->id ? 'selected' : '' }}>
                            {{ $ta->tahun_ajaran }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                <i class="fas fa-search mr-2"></i>Filter
            </button>
        </form>
    </div>

    <!-- Tabel Data -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Peserta</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NISN</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Asal Sekolah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun Ajaran</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl Daftar</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($pendaftar as $index => $siswa)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $pendaftar->firstItem() + $index }}</td>
                        <td class="px-6 py-4 font-mono text-sm">{{ $siswa->no_peserta }}</td>
                        <td class="px-6 py-4">{{ $siswa->nama_lengkap }}</td>
                        <td class="px-6 py-4">{{ $siswa->nisn }}</td>
                        <td class="px-6 py-4">{{ $siswa->sekolah_asal }}</td>
                        <td class="px-6 py-4">{{ $siswa->tahunAjaran->tahun_ajaran ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $siswa->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('pendaftaran.show', $siswa->id) }}"
                                    class="text-blue-600 hover:text-blue-900 bg-blue-100 p-2 rounded"
                                    title="Lihat detail">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <a href="{{ route('pendaftaran.cetak', $siswa->id) }}" target="_blank"
                                    class="text-green-600 hover:text-green-900 bg-green-100 p-2 rounded"
                                    title="Cetak kartu">
                                    <i class="fas fa-print"></i>
                                </a>

                                @if (auth()->user()->role === 'admin')
                                    <form action="{{ route('pendaftaran.destroy', $siswa->id) }}" method="POST"
                                        class="inline" onsubmit="return confirm('Pindahkan data ini ke trash?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-900 bg-red-100 p-2 rounded"
                                            title="Hapus (pindah ke trash)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-users text-5xl mb-4"></i>
                                <p class="text-lg">Belum ada data pendaftar</p>
                                <p class="text-sm">Silakan tambah data pendaftar baru</p>
                                <a href="{{ route('pendaftaran.create') }}"
                                    class="inline-block mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                    <i class="fas fa-plus mr-2"></i>Tambah Pendaftar
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $pendaftar->appends(request()->query())->links() }}
    </div>

    <!-- Tambahkan Alpine.js untuk dropdown -->
    <script src="//unpkg.com/alpinejs" defer></script>
@endsection
