@extends('layouts.app')

@section('title', 'Trash - Data Terhapus')

@section('content')
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold">Data Siswa Terhapus</h1>
                <p class="text-gray-600">Data yang telah dihapus sementara (soft delete)</p>
            </div>
            <div class="flex space-x-2">
                <form action="{{ route('pendaftaran.restore-all') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" onclick="return confirm('Kembalikan semua data?')"
                        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                        <i class="fas fa-undo mr-2"></i>Restore All
                    </button>
                </form>
                <form action="{{ route('pendaftaran.empty-trash') }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        onclick="return confirm('Hapus permanen semua data? Tindakan ini tidak dapat dibatalkan!')"
                        class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                        <i class="fas fa-trash-alt mr-2"></i>Empty Trash
                    </button>
                </form>
                <a href="{{ route('pendaftaran.index') }}"
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Data Aktif
                </a>
            </div>
        </div>
    </div>

    <!-- Search Box -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form action="{{ route('pendaftaran.trash') }}" method="GET" class="flex gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari berdasarkan nama, nomor peserta, atau NISN..."
                    class="w-full border rounded px-4 py-2">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                <i class="fas fa-search mr-2"></i>Cari
            </button>
        </form>
    </div>

    <!-- Tabel Data Trash -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Peserta</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NISN</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun Ajaran</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Dihapus</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dihapus Oleh</th>
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
                        <td class="px-6 py-4">{{ $siswa->tahunAjaran->tahun_ajaran ?? '-' }}</td>
                        <td class="px-6 py-4">
                            {{ $siswa->deleted_at->format('d/m/Y H:i') }}
                            <br>
                            <span class="text-xs text-gray-500">{{ $siswa->deleted_at->diffForHumans() }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <!-- Ini perlu ditambahkan fitur user tracking -->
                            <span class="text-gray-500">-</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <form action="{{ route('pendaftaran.restore', $siswa->id) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="text-green-600 hover:text-green-900 bg-green-100 p-2 rounded"
                                        title="Kembalikan data" onclick="return confirm('Kembalikan data ini?')">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                </form>

                                <form action="{{ route('pendaftaran.force-delete', $siswa->id) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 bg-red-100 p-2 rounded"
                                        title="Hapus permanen"
                                        onclick="return confirm('Hapus permanen data ini? Tindakan ini tidak dapat dibatalkan!')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>

                                <a href="{{ route('pendaftaran.show', $siswa->id) }}"
                                    class="text-blue-600 hover:text-blue-900 bg-blue-100 p-2 rounded" title="Lihat detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-trash-alt text-5xl mb-4"></i>
                                <p class="text-lg">Tidak ada data di trash</p>
                                <p class="text-sm">Data yang dihapus akan muncul di sini</p>
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

    <!-- Info Box -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
            <div>
                <h4 class="font-semibold text-blue-800">Informasi Soft Delete:</h4>
                <ul class="text-sm text-blue-700 list-disc list-inside mt-2">
                    <li>Data di trash masih bisa dikembalikan (restore)</li>
                    <li>Restore All akan mengembalikan semua data ke daftar aktif</li>
                    <li>Empty Trash akan menghapus permanen semua data di trash</li>
                    <li>Data yang dihapus permanen tidak dapat dikembalikan</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
