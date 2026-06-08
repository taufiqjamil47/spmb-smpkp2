@extends('layouts.app')

@section('title', 'Trash - Data Terhapus')

@section('content')
    <div class="div mt-8">
        <!-- Header Section with Gradient -->
        <div
            class="mb-8 bg-gradient-to-r from-red-600 to-orange-600 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden">
            <div class="absolute bottom-0 left-0 opacity-5">
                <i class="fas fa-trash-alt text-8xl mb-2"></i>
            </div>
            <div class="relative z-10">
                <div class="flex justify-between items-center flex-wrap gap-4">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Data Siswa Terhapus</h1>
                        <p class="text-red-100">Data yang telah dihapus sementara (soft delete) - masih dapat dipulihkan</p>
                    </div>
                    <div class="flex space-x-3 flex-wrap gap-2">
                        <button type="button" id="restoreAllBtn"
                            class="bg-green-500 hover:bg-green-600 text-white px-5 py-2.5 rounded-xl transition-all font-medium shadow-md hover:shadow-lg">
                            <i class="fas fa-undo mr-2"></i>Restore All
                        </button>
                        <button type="button" id="emptyTrashBtn"
                            class="bg-red-500 hover:bg-red-600 text-white px-5 py-2.5 rounded-xl transition-all font-medium shadow-md hover:shadow-lg">
                            <i class="fas fa-trash-alt mr-2"></i>Empty Trash
                        </button>
                        <a href="{{ route('pendaftaran.index') }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2.5 rounded-xl transition-all font-medium shadow-md hover:shadow-lg">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Box dengan Desain Modern -->
        <div class="bg-white rounded-2xl shadow-xl p-5 mb-8">
            <form action="{{ route('pendaftaran.trash') }}" method="GET" class="flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-[250px]">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                        <i class="fas fa-search mr-1"></i> Cari Data Terhapus
                    </label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari berdasarkan nama, nomor peserta, atau NISN..."
                            class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:border-red-400 focus:ring-2 focus:ring-red-200 transition-all">
                    </div>
                </div>
                <div>
                    <button type="submit"
                        class="bg-gradient-to-r from-red-500 to-red-600 text-white px-8 py-2.5 rounded-xl hover:shadow-lg transition-all font-medium">
                        <i class="fas fa-search mr-2"></i>Cari
                    </button>
                </div>
                @if (request('search'))
                    <div>
                        <a href="{{ route('pendaftaran.trash') }}"
                            class="bg-gray-100 text-gray-600 px-5 py-2.5 rounded-xl hover:bg-gray-200 transition-all flex items-center">
                            <i class="fas fa-times mr-2"></i>Reset
                        </a>
                    </div>
                @endif
            </form>
        </div>

        <!-- Tabel Data Trash -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No.
                                Peserta</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Nama</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                NISN</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Tahun Ajaran</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Tanggal Dihapus</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($pendaftar as $index => $siswa)
                            <tr class="hover:bg-red-50 transition-colors group" data-student-id="{{ $siswa->id }}">
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-gray-700">{{ $pendaftar->firstItem() + $index }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="font-mono text-sm font-semibold text-gray-800 bg-gray-100 px-2 py-1 rounded-lg">{{ $siswa->no_peserta }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900">{{ $siswa->nama_lengkap }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $siswa->nisn ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 font-mono text-sm text-gray-700">{{ $siswa->nisn }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs">{{ $siswa->tahunAjaran->tahun_ajaran ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-medium text-gray-700">{{ $siswa->deleted_at->format('d/m/Y H:i') }}</span>
                                        <span
                                            class="text-xs text-gray-400">{{ $siswa->deleted_at->diffForHumans() }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-1.5">
                                        <button type="button"
                                            class="restore-btn text-green-600 hover:text-white hover:bg-green-600 p-2 rounded-lg transition-all"
                                            data-id="{{ $siswa->id }}" data-name="{{ $siswa->nama_lengkap }}"
                                            data-no-peserta="{{ $siswa->no_peserta }}" title="Kembalikan data">
                                            <i class="fas fa-undo"></i>
                                        </button>

                                        <button type="button"
                                            class="force-delete-btn text-red-600 hover:text-white hover:bg-red-600 p-2 rounded-lg transition-all"
                                            data-id="{{ $siswa->id }}" data-name="{{ $siswa->nama_lengkap }}"
                                            data-no-peserta="{{ $siswa->no_peserta }}" title="Hapus permanen">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>

                                        <a href="{{ route('pendaftaran.show', $siswa->id) }}"
                                            class="text-blue-600 hover:text-white hover:bg-blue-600 p-2 rounded-lg transition-all"
                                            title="Lihat detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>

                                    <!-- Hidden forms untuk aksi individual -->
                                    <form id="restore-form-{{ $siswa->id }}"
                                        action="{{ route('pendaftaran.restore', $siswa->id) }}" method="POST"
                                        class="hidden">
                                        @csrf
                                    </form>

                                    <form id="force-delete-form-{{ $siswa->id }}"
                                        action="{{ route('pendaftaran.force-delete', $siswa->id) }}" method="POST"
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
                                            <i class="fas fa-trash-alt text-4xl text-gray-400"></i>
                                        </div>
                                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak Ada Data di Trash</h3>
                                        <p class="text-gray-500">Data yang dihapus sementara akan muncul di sini</p>
                                        <a href="{{ route('pendaftaran.index') }}"
                                            class="inline-flex items-center mt-6 bg-blue-500 text-white px-5 py-2.5 rounded-xl hover:bg-blue-600 transition-all">
                                            <i class="fas fa-arrow-left mr-2"></i>
                                            Kembali ke Data Aktif
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if ($pendaftar->hasPages())
            <div class="mt-8">
                <div class="bg-white rounded-xl shadow-md p-4">
                    {{ $pendaftar->appends(request()->query())->links() }}
                </div>
            </div>
        @endif

        <!-- Info Box dengan Desain Premium -->
        <div class="mt-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200 p-5">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div
                        class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-info-circle text-white"></i>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h4 class="font-bold text-blue-800 text-lg">Informasi Soft Delete</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-3">
                        <div class="flex items-start">
                            <i class="fas fa-undo text-green-600 mt-0.5 mr-2 text-sm"></i>
                            <span class="text-sm text-blue-700">Data di trash masih bisa dikembalikan (restore)</span>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-layer-group text-yellow-600 mt-0.5 mr-2 text-sm"></i>
                            <span class="text-sm text-blue-700">Restore All akan mengembalikan semua data ke daftar
                                aktif</span>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-trash-alt text-red-600 mt-0.5 mr-2 text-sm"></i>
                            <span class="text-sm text-blue-700">Empty Trash akan menghapus permanen semua data di
                                trash</span>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-orange-600 mt-0.5 mr-2 text-sm"></i>
                            <span class="text-sm text-blue-700">Data yang dihapus permanen tidak dapat dikembalikan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden forms untuk bulk actions -->
    <form id="restore-all-form" action="{{ route('pendaftaran.restore-all') }}" method="POST" class="hidden">
        @csrf
    </form>

    <form id="empty-trash-form" action="{{ route('pendaftaran.empty-trash') }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

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
                background: linear-gradient(135deg, #EF4444, #DC2626);
                color: white;
                border-color: transparent;
            }

            .pagination .page-item:not(.disabled) .page-link:hover {
                background-color: #FEF2F2;
                border-color: #EF4444;
                color: #DC2626;
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
            // Restore button handlers (individual)
            document.querySelectorAll('.restore-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const studentId = this.dataset.id;
                    const studentName = this.dataset.name;
                    const studentNoPeserta = this.dataset.noPeserta;

                    Swal.fire({
                        title: 'Kembalikan Data?',
                        html: `
                            <div class="text-left">
                                <p class="mb-3">Anda akan mengembalikan data siswa berikut ke daftar aktif:</p>
                                <div class="bg-gray-50 rounded-lg p-3 mb-3">
                                    <p class="font-semibold text-gray-800">${studentName}</p>
                                    <p class="text-sm text-gray-500 font-mono">No. Peserta: ${studentNoPeserta}</p>
                                </div>
                                <div class="bg-green-50 border-l-4 border-green-400 p-3 mt-2">
                                    <p class="text-sm text-green-700">
                                        <i class="fas fa-info-circle mr-1"></i> 
                                        Data akan dikembalikan ke daftar pendaftar aktif.
                                    </p>
                                </div>
                            </div>
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#10B981',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: 'Ya, Kembalikan!',
                        cancelButtonText: 'Batal',
                        showLoaderOnConfirm: true,
                        preConfirm: async () => {
                            try {
                                const form = document.getElementById(`restore-form-${studentId}`);
                                form.submit();
                                return true;
                            } catch (error) {
                                Swal.showValidationMessage(`Request failed: ${error}`);
                            }
                        }
                    });
                });
            });

            // Force Delete button handlers (individual permanent delete)
            document.querySelectorAll('.force-delete-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const studentId = this.dataset.id;
                    const studentName = this.dataset.name;
                    const studentNoPeserta = this.dataset.noPeserta;

                    Swal.fire({
                        title: 'Hapus Permanen?',
                        html: `
                            <div class="text-left">
                                <p class="mb-3">Anda akan menghapus data siswa berikut secara permanen:</p>
                                <div class="bg-gray-50 rounded-lg p-3 mb-3">
                                    <p class="font-semibold text-gray-800">${studentName}</p>
                                    <p class="text-sm text-gray-500 font-mono">No. Peserta: ${studentNoPeserta}</p>
                                </div>
                                <div class="bg-red-50 border-l-4 border-red-400 p-3 mt-2">
                                    <p class="text-sm text-red-700 font-semibold">
                                        <i class="fas fa-exclamation-triangle mr-1"></i> 
                                        PERINGATAN!
                                    </p>
                                    <p class="text-sm text-red-600 mt-1">
                                        Data akan dihapus secara permanen dari database dan TIDAK DAPAT DIKEMBALIKAN!
                                    </p>
                                </div>
                            </div>
                        `,
                        icon: 'error',
                        showCancelButton: true,
                        confirmButtonColor: '#EF4444',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: 'Ya, Hapus Permanen!',
                        cancelButtonText: 'Batal',
                        showLoaderOnConfirm: true,
                        preConfirm: async () => {
                            try {
                                const form = document.getElementById(
                                    `force-delete-form-${studentId}`);
                                form.submit();
                                return true;
                            } catch (error) {
                                Swal.showValidationMessage(`Request failed: ${error}`);
                            }
                        }
                    });
                });
            });

            // Restore All button handler
            const restoreAllBtn = document.getElementById('restoreAllBtn');
            if (restoreAllBtn) {
                restoreAllBtn.addEventListener('click', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Kembalikan Semua Data?',
                        html: `
                            <div class="text-left">
                                <p class="mb-3">Anda akan mengembalikan SEMUA data yang ada di trash ke daftar aktif.</p>
                                <div class="bg-green-50 border-l-4 border-green-400 p-3 mt-2">
                                    <p class="text-sm text-green-700">
                                        <i class="fas fa-info-circle mr-1"></i> 
                                        Semua data siswa di trash akan dikembalikan ke daftar pendaftar aktif.
                                    </p>
                                </div>
                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 mt-2">
                                    <p class="text-sm text-yellow-700">
                                        <i class="fas fa-clock mr-1"></i> 
                                        Proses ini mungkin memerlukan waktu jika jumlah data banyak.
                                    </p>
                                </div>
                            </div>
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#10B981',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: 'Ya, Kembalikan Semua!',
                        cancelButtonText: 'Batal',
                        showLoaderOnConfirm: true,
                        preConfirm: async () => {
                            try {
                                const form = document.getElementById('restore-all-form');
                                form.submit();
                                return true;
                            } catch (error) {
                                Swal.showValidationMessage(`Request failed: ${error}`);
                            }
                        }
                    });
                });
            }

            // Empty Trash button handler
            const emptyTrashBtn = document.getElementById('emptyTrashBtn');
            if (emptyTrashBtn) {
                emptyTrashBtn.addEventListener('click', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Kosongkan Trash?',
                        html: `
                            <div class="text-left">
                                <p class="mb-3">Anda akan menghapus SEMUA data di trash secara permanen.</p>
                                <div class="bg-red-50 border-l-4 border-red-400 p-3 mb-3">
                                    <p class="text-sm text-red-700 font-semibold">
                                        <i class="fas fa-exclamation-triangle mr-1"></i> 
                                        PERINGATAN SERIUS!
                                    </p>
                                    <p class="text-sm text-red-600 mt-1">
                                        Tindakan ini akan menghapus SEMUA data siswa yang ada di trash secara permanen!
                                    </p>
                                    <p class="text-sm text-red-600 mt-1 font-bold">
                                        Data yang dihapus tidak dapat dikembalikan lagi!
                                    </p>
                                </div>
                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 mt-2">
                                    <p class="text-sm text-yellow-700">
                                        <i class="fas fa-clock mr-1"></i> 
                                        Jumlah data yang akan dihapus: <strong>{{ $pendaftar->total() }}</strong> siswa
                                    </p>
                                </div>
                            </div>
                        `,
                        icon: 'error',
                        showCancelButton: true,
                        confirmButtonColor: '#EF4444',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: 'Ya, Kosongkan Trash!',
                        cancelButtonText: 'Batal',
                        showLoaderOnConfirm: true,
                        preConfirm: async () => {
                            try {
                                const form = document.getElementById('empty-trash-form');
                                form.submit();
                                return true;
                            } catch (error) {
                                Swal.showValidationMessage(`Request failed: ${error}`);
                            }
                        }
                    });
                });
            }

            // Flash message notifications
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#10B981',
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
        </script>
    @endpush
@endsection
