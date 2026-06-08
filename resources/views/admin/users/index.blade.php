@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
    <div class="div mt-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="bg-white bg-opacity-20 rounded-lg p-3">
                        <i class="fas fa-users text-white text-3xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white">{{ __('Manajemen User') }}</h1>
                        <p class="text-blue-100 mt-1">Kelola semua pengguna sistem</p>
                    </div>
                </div>
                <a href="{{ route('users.create') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-white text-blue-600 font-semibold rounded-lg hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 transition duration-200">
                    <i class="fas fa-plus"></i>
                    {{ __('Tambah User') }}
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm uppercase tracking-wide">Total User</p>
                        <p class="text-3xl font-bold text-blue-600 mt-2">{{ $users->total() }}</p>
                    </div>
                    <div class="bg-blue-100 rounded-lg p-3">
                        <i class="fas fa-users text-blue-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm uppercase tracking-wide">Admin</p>
                        <p class="text-3xl font-bold text-purple-600 mt-2">
                            {{ \App\Models\User::where('role', 'admin')->count() }}</p>
                    </div>
                    <div class="bg-purple-100 rounded-lg p-3">
                        <i class="fas fa-crown text-purple-600 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm uppercase tracking-wide">Regular User</p>
                        <p class="text-3xl font-bold text-green-600 mt-2">
                            {{ \App\Models\User::where('role', 'petugas')->count() }}
                        </p>
                    </div>
                    <div class="bg-green-100 rounded-lg p-3">
                        <i class="fas fa-user text-green-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                                <i class="fas fa-hashtag text-gray-400 mr-2"></i>ID
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                                <i class="fas fa-user text-gray-400 mr-2"></i>Nama
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                                <i class="fas fa-envelope text-gray-400 mr-2"></i>Email
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                                <i class="fas fa-tag text-gray-400 mr-2"></i>Role
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                                <i class="fas fa-calendar text-gray-400 mr-2"></i>Terdaftar
                            </th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">
                                <i class="fas fa-cog text-gray-400 mr-2"></i>Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($users as $user)
                            <tr class="hover:bg-gray-50 transition duration-200" data-user-id="{{ $user->id }}">
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        #{{ $user->id }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="flex items-center justify-center h-10 w-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="font-semibold">{{ $user->name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if ($user->role === 'admin')
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                            <i class="fas fa-crown mr-1"></i>
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            <i class="fas fa-user-check mr-1"></i>
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('users.show', $user->id) }}"
                                            class="inline-flex items-center gap-1 px-3 py-2 text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition duration-200 group"
                                            title="Lihat detail">
                                            <i class="fas fa-eye group-hover:scale-110 transition"></i>
                                        </a>
                                        <a href="{{ route('users.edit', $user->id) }}"
                                            class="inline-flex items-center gap-1 px-3 py-2 text-xs font-medium text-yellow-600 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition duration-200 group"
                                            title="Edit user">
                                            <i class="fas fa-edit group-hover:scale-110 transition"></i>
                                        </a>
                                        <button type="button"
                                            class="delete-btn inline-flex items-center gap-1 px-3 py-2 text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition duration-200 group"
                                            data-id="{{ $user->id }}" data-name="{{ $user->name }}"
                                            data-email="{{ $user->email }}" data-role="{{ $user->role }}"
                                            title="Hapus user">
                                            <i class="fas fa-trash-alt group-hover:scale-110 transition"></i>
                                        </button>
                                    </div>

                                    <!-- Hidden form untuk delete -->
                                    <form id="delete-form-{{ $user->id }}"
                                        action="{{ route('users.destroy', $user->id) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <i class="fas fa-inbox text-gray-300 text-4xl"></i>
                                        <p class="text-gray-500 font-medium">Tidak ada user ditemukan</p>
                                        <p class="text-gray-400 text-sm">Mulai dengan menambahkan user baru</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
        <!-- Load SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            // Delete button handlers with SweetAlert
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const userId = this.dataset.id;
                    const userName = this.dataset.name;
                    const userEmail = this.dataset.email;
                    const userRole = this.dataset.role;

                    // Cek apakah user mencoba menghapus dirinya sendiri
                    const isSelf = userId === '{{ auth()->id() }}';

                    let warningHtml = `
                        <div class="text-left">
                            <p class="mb-3">Anda akan menghapus user berikut:</p>
                            <div class="bg-gray-50 rounded-lg p-3 mb-3">
                                <p class="font-semibold text-gray-800">${userName}</p>
                                <p class="text-sm text-gray-500">Email: ${userEmail}</p>
                                <p class="text-sm text-gray-500 mt-1">
                                    Role: 
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold ${userRole === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800'}">
                                        ${userRole === 'admin' ? '<i class="fas fa-crown mr-1"></i>' : '<i class="fas fa-user-check mr-1"></i>'}
                                        ${userRole.charAt(0).toUpperCase() + userRole.slice(1)}
                                    </span>
                                </p>
                            </div>
                    `;

                    if (isSelf) {
                        warningHtml += `
                            <div class="bg-red-50 border-l-4 border-red-500 p-3 mt-2">
                                <p class="text-sm text-red-700 font-semibold">
                                    <i class="fas fa-exclamation-triangle mr-1"></i> 
                                    PERINGATAN PENTING!
                                </p>
                                <p class="text-sm text-red-600 mt-1">
                                    Anda sedang mencoba menghapus akun Anda sendiri (akun yang sedang aktif).
                                    Tindakan ini akan menghapus akun Anda secara permanen dan Anda akan keluar dari sistem.
                                </p>
                            </div>
                        `;
                    } else {
                        warningHtml += `
                            <div class="bg-red-50 border-l-4 border-red-400 p-3 mt-2">
                                <p class="text-sm text-red-700">
                                    <i class="fas fa-exclamation-triangle mr-1"></i> 
                                    Data user akan dihapus secara permanen dan tidak dapat dikembalikan!
                                </p>
                            </div>
                        `;
                    }

                    warningHtml += `</div>`;

                    Swal.fire({
                        title: isSelf ? 'Hapus Akun Sendiri?' : 'Hapus User?',
                        html: warningHtml,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#EF4444',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: isSelf ? 'Ya, Hapus Akun Saya!' : 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        showLoaderOnConfirm: true,
                        preConfirm: async () => {
                            try {
                                const form = document.getElementById(`delete-form-${userId}`);
                                form.submit();
                                return true;
                            } catch (error) {
                                Swal.showValidationMessage(`Request failed: ${error}`);
                            }
                        }
                    });
                });
            });

            // Flash message notifications with SweetAlert
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
        </script>
    @endpush
@endsection
