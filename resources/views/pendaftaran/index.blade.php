@extends('layouts.app')

@section('title', 'Data Pendaftar')

@section('content')
    <div class="div mt-8">
        <!-- Welcome Section -->
        <div class="mb-8 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-lg p-6 text-white relative">
            <div class="absolute top-0 right-0 opacity-10">
                {{-- <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L15 8.5L22 9.5L17 14L18.5 21L12 17.5L5.5 21L7 14L2 9.5L9 8.5L12 2Z" />
                </svg> --}}
            </div>
            <div class="relative z-10">
                <div class="flex justify-between items-center flex-wrap gap-4">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Data Calon Siswa</h1>
                        <div class="flex items-center space-x-3">
                            <p class="text-blue-100">Kelola data pendaftar dengan mudah</p>
                            <span class="px-2 py-1 bg-white bg-opacity-20 rounded-lg text-sm">
                                <i class="fas fa-users mr-1"></i> Total: {{ $pendaftar->total() }} pendaftar
                            </span>
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        @if (auth()->user()->role === 'admin')
                            <!-- Tombol Export Dropdown dengan Desain Modern -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open"
                                    class="bg-white text-blue-600 px-5 py-2.5 rounded-xl hover:bg-gray-100 flex items-center font-medium shadow-md transition-all">
                                    <i class="fas fa-download mr-2"></i>
                                    Export
                                    <i class="fas fa-chevron-down ml-2 text-xs"></i>
                                </button>

                                <div x-show="open" @click.away="open = false" x-transition
                                    class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-2xl z-50 border border-gray-100 overflow-hidden">
                                    <div class="p-3 bg-gradient-to-r from-gray-50 to-gray-100 border-b">
                                        <p class="font-semibold text-gray-800 text-sm">
                                            <i class="fas fa-download mr-2 text-green-600"></i>Pilih Format Export
                                        </p>
                                    </div>

                                    <div class="p-2">
                                        <form action="{{ route('export.excel') }}" method="GET" class="mb-1"
                                            @submit="open = false">
                                            <input type="hidden" name="tahun" value="{{ request('tahun') }}">
                                            <input type="hidden" name="status" value="aktif">
                                            <button type="submit"
                                                class="w-full text-left px-3 py-2.5 hover:bg-green-50 rounded-lg flex items-center transition-colors group">
                                                <i class="fas fa-file-excel text-green-600 w-6 mr-3 text-lg"></i>
                                                <div class="flex-1">
                                                    <p class="font-medium text-gray-700">Excel (.xlsx)</p>
                                                    <p class="text-xs text-gray-400">Format standar Microsoft Excel</p>
                                                </div>
                                            </button>
                                        </form>

                                        <form action="{{ route('export.csv') }}" method="GET" class="mb-1"
                                            @submit="open = false">
                                            <input type="hidden" name="tahun" value="{{ request('tahun') }}">
                                            <input type="hidden" name="status" value="aktif">
                                            <button type="submit"
                                                class="w-full text-left px-3 py-2.5 hover:bg-blue-50 rounded-lg flex items-center transition-colors group">
                                                <i class="fas fa-file-csv text-blue-600 w-6 mr-3 text-lg"></i>
                                                <div class="flex-1">
                                                    <p class="font-medium text-gray-700">CSV (.csv)</p>
                                                    <p class="text-xs text-gray-400">Comma Separated Values</p>
                                                </div>
                                            </button>
                                        </form>

                                        <div class="border-t my-2"></div>

                                        <form action="{{ route('export.excel') }}" method="GET" class="mb-1"
                                            @submit="open = false">
                                            <input type="hidden" name="tahun" value="{{ request('tahun') }}">
                                            <input type="hidden" name="status" value="all">
                                            <button type="submit"
                                                class="w-full text-left px-3 py-2.5 hover:bg-purple-50 rounded-lg flex items-center transition-colors">
                                                <i class="fas fa-database text-purple-600 w-6 mr-3 text-lg"></i>
                                                <div class="flex-1">
                                                    <p class="font-medium text-gray-700">Excel - Semua Data</p>
                                                    <p class="text-xs text-gray-400">Termasuk data yang di-trash</p>
                                                </div>
                                            </button>
                                        </form>

                                        <form action="{{ route('export.excel') }}" method="GET" class="mb-1"
                                            @submit="open = false">
                                            <input type="hidden" name="status" value="trash">
                                            <button type="submit"
                                                class="w-full text-left px-3 py-2.5 hover:bg-red-50 rounded-lg flex items-center transition-colors">
                                                <i class="fas fa-trash-alt text-red-600 w-6 mr-3 text-lg"></i>
                                                <div class="flex-1">
                                                    <p class="font-medium text-gray-700">Excel - Hanya Trash</p>
                                                    <p class="text-xs text-gray-400">Data yang sudah dihapus</p>
                                                </div>
                                            </button>
                                        </form>
                                    </div>

                                    <div class="p-2 bg-gradient-to-r from-gray-50 to-gray-100 border-t">
                                        <p class="text-xs text-gray-500 flex items-center">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Data akan difilter berdasarkan pencarian saat ini
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('pendaftaran.trash') }}"
                                class="bg-gradient-to-r from-orange-500 to-red-500 text-white px-5 py-2.5 rounded-xl hover:shadow-lg transition-all flex items-center font-medium">
                                <i class="fas fa-trash-alt mr-2"></i>
                                Trash
                                @if ($trashCount > 0)
                                    <span class="ml-2 bg-white text-orange-600 px-2 py-0.5 rounded-full text-xs font-bold">
                                        {{ $trashCount }}
                                    </span>
                                @endif
                            </a>
                        @endif
                        <a href="{{ route('pendaftaran.create') }}"
                            class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-5 py-2.5 rounded-xl hover:shadow-lg transition-all flex items-center font-medium">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Pendaftar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search dengan Desain Modern -->
        <div class="bg-white rounded-2xl shadow-xl p-5 mb-8">
            <form action="{{ route('pendaftaran.index') }}" method="GET" class="flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                        <i class="fas fa-search mr-1"></i> Cari Data
                    </label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama, no. peserta, atau NISN..."
                            class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all">
                    </div>
                </div>
                <div class="w-64">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                        <i class="fas fa-calendar-alt mr-1"></i> Filter Tahun
                    </label>
                    <div class="relative">
                        <i class="fas fa-calendar absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <select name="tahun"
                            class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all appearance-none bg-white cursor-pointer">
                            <option value="">Semua Tahun</option>
                            @foreach ($tahunAjaran as $ta)
                                <option value="{{ $ta->id }}" {{ request('tahun') == $ta->id ? 'selected' : '' }}>
                                    {{ $ta->tahun_ajaran }}
                                </option>
                            @endforeach
                        </select>
                        <i
                            class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>
                <div>
                    <button type="submit"
                        class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-8 py-2.5 rounded-xl hover:shadow-lg transition-all flex items-center font-medium">
                        <i class="fas fa-filter mr-2"></i>
                        Terapkan Filter
                    </button>
                </div>
                @if (request('search') || request('tahun'))
                    <div>
                        <a href="{{ route('pendaftaran.index') }}"
                            class="bg-gray-100 text-gray-600 px-5 py-2.5 rounded-xl hover:bg-gray-200 transition-all flex items-center">
                            <i class="fas fa-times mr-2"></i>
                            Reset
                        </a>
                    </div>
                @endif
            </form>
        </div>

        <!-- Tabel Data dengan Desain Premium -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                No. Peserta</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Nama</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                NISN</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Asal Sekolah</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Tgl Daftar</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($pendaftar as $index => $siswa)
                            <tr class="hover:bg-blue-50 transition-colors group" data-student-id="{{ $siswa->id }}">
                                <td class="px-6 py-4">
                                    <span
                                        class="font-semibold text-gray-700">{{ $pendaftar->firstItem() + $index }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="font-mono text-sm font-semibold text-gray-800 bg-gray-100 px-2 py-1 rounded-lg">{{ $siswa->no_peserta }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900">{{ $siswa->nama_lengkap }}</div>
                                    <div class="text-xs text-gray-500">{{ $siswa->jenis_kelamin ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 font-mono text-sm text-gray-700">{{ $siswa->nisn ?? '-' }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $siswa->sekolah_asal ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-calendar-alt text-gray-400 mr-2 text-xs"></i>
                                        {{ $siswa->created_at->format('d/m/Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-1.5">
                                        <a href="{{ route('pendaftaran.show', $siswa->id) }}"
                                            class="text-blue-600 hover:text-white hover:bg-blue-600 p-2 rounded-lg transition-all"
                                            title="Lihat detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a href="{{ route('pendaftaran.cetak', $siswa->id) }}" target="_blank"
                                            class="text-green-600 hover:text-white hover:bg-green-600 p-2 rounded-lg transition-all"
                                            title="Cetak kartu">
                                            <i class="fas fa-print"></i>
                                        </a>

                                        @if (auth()->user()->role === 'admin')
                                            <a href="{{ route('pendaftaran.edit', $siswa->id) }}"
                                                class="text-yellow-600 hover:text-white hover:bg-yellow-600 p-2 rounded-lg transition-all"
                                                title="Edit data">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif

                                        @if (auth()->user()->role === 'admin')
                                            <button type="button"
                                                class="delete-btn text-red-600 hover:text-white hover:bg-red-600 p-2 rounded-lg transition-all"
                                                data-id="{{ $siswa->id }}" data-name="{{ $siswa->nama_lengkap }}"
                                                data-no-peserta="{{ $siswa->no_peserta }}"
                                                title="Hapus (pindah ke trash)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>

                                    <!-- Hidden form untuk delete -->
                                    <form id="delete-form-{{ $siswa->id }}"
                                        action="{{ route('pendaftaran.destroy', $siswa->id) }}" method="POST"
                                        class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div
                                            class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-users text-4xl text-gray-400"></i>
                                        </div>
                                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Data Pendaftar</h3>
                                        <p class="text-gray-500 mb-6">Silakan tambah data pendaftar baru terlebih dahulu
                                        </p>
                                        <a href="{{ route('pendaftaran.create') }}"
                                            class="inline-flex items-center bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-3 rounded-xl hover:shadow-lg transition-all">
                                            <i class="fas fa-plus mr-2"></i>
                                            Tambah Pendaftar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination dengan Desain Modern -->
        @if ($pendaftar->hasPages())
            <div class="mt-8">
                <div class="bg-white rounded-xl shadow-md p-4">
                    {{ $pendaftar->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- Tambahkan Alpine.js untuk dropdown -->
    <script src="//unpkg.com/alpinejs" defer></script>

    @push('styles')
        <style>
            /* Custom Pagination Styling */
            .pagination {
                display: flex;
                justify-content: center;
                gap: 0.5rem;
                flex-wrap: wrap;
            }

            .pagination .page-item .page-link {
                padding: 0.5rem 1rem;
                border-radius: 0.75rem;
                color: #4B5563;
                background-color: white;
                border: 1px solid #E5E7EB;
                transition: all 0.2s;
            }

            .pagination .page-item.active .page-link {
                background: linear-gradient(135deg, #3B82F6, #2563EB);
                color: white;
                border-color: transparent;
            }

            .pagination .page-item:not(.disabled) .page-link:hover {
                background-color: #EFF6FF;
                border-color: #3B82F6;
                color: #2563EB;
            }

            .pagination .page-item.disabled .page-link {
                opacity: 0.5;
                cursor: not-allowed;
            }
        </style>
    @endpush

    @push('scripts')
        <!-- Load SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            // Delete button handlers with SweetAlert
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const studentId = this.dataset.id;
                    const studentName = this.dataset.name;
                    const studentNoPeserta = this.dataset.noPeserta;

                    Swal.fire({
                        title: 'Pindahkan ke Trash?',
                        html: `
                            <div class="text-left">
                                <p class="mb-3">Anda akan memindahkan data pendaftar berikut ke trash:</p>
                                <div class="bg-gray-50 rounded-lg p-3 mb-3">
                                    <p class="font-semibold text-gray-800">${studentName}</p>
                                    <p class="text-sm text-gray-500 font-mono">No. Peserta: ${studentNoPeserta}</p>
                                </div>
                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 mt-2">
                                    <p class="text-sm text-yellow-800">
                                        <i class="fas fa-exclamation-triangle mr-1"></i> 
                                        Data akan dipindahkan ke trash dan tidak akan tampil di daftar utama. 
                                        Anda masih dapat memulihkannya nanti.
                                    </p>
                                </div>
                            </div>
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#EF4444',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: 'Ya, Pindahkan!',
                        cancelButtonText: 'Batal',
                        showLoaderOnConfirm: true,
                        preConfirm: async () => {
                            try {
                                const form = document.getElementById(`delete-form-${studentId}`);
                                form.submit();
                                return true;
                            } catch (error) {
                                Swal.showValidationMessage(`Request failed: ${error}`);
                            }
                        }
                    });
                });
            });

            // Flash message notifications
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#3B82F6',
                    timer: 3000,
                    showConfirmButton: true
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#EF4444'
                });
            @endif

            @if (session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan!',
                    text: '{{ session('warning') }}',
                    confirmButtonColor: '#F59E0B'
                });
            @endif

            @if (session('info'))
                Swal.fire({
                    icon: 'info',
                    title: 'Informasi',
                    text: '{{ session('info') }}',
                    confirmButtonColor: '#3B82F6'
                });
            @endif
        </script>
    @endpush
@endsection
