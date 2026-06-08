@extends('layouts.app')

@section('title', $user->name)

@section('content')
    <div class="div mt-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-blue-600 rounded-2xl shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="bg-white bg-opacity-20 rounded-lg p-3">
                        <i class="fas fa-user-circle text-white text-3xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white">{{ $user->name }}</h1>
                        <p class="text-blue-100 mt-1">Detail pengguna</p>
                    </div>
                </div>
                <a href="{{ route('users.index') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-white text-indigo-600 font-semibold rounded-lg hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 transition duration-200">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('Kembali') }}
                </a>
            </div>
        </div>

        <!-- User Details -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Main Info -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-50 to-blue-50 p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-info-circle text-indigo-600 mr-2"></i>
                            {{ __('Informasi Pengguna') }}
                        </h2>
                    </div>

                    <div class="p-6 space-y-6">
                        <!-- ID -->
                        <div class="pb-6 border-b border-gray-200">
                            <p class="text-gray-600 text-sm uppercase tracking-wide mb-2">
                                <i class="fas fa-hashtag text-gray-400 mr-1"></i>
                                User ID
                            </p>
                            <p class="text-2xl font-bold text-gray-900">#{{ $user->id }}</p>
                        </div>

                        <!-- Nama -->
                        <div class="pb-6 border-b border-gray-200">
                            <p class="text-gray-600 text-sm uppercase tracking-wide mb-2">
                                <i class="fas fa-user text-gray-400 mr-1"></i>
                                Nama Lengkap
                            </p>
                            <p class="text-lg font-semibold text-gray-900">{{ $user->name }}</p>
                        </div>

                        <!-- Email -->
                        <div class="pb-6 border-b border-gray-200">
                            <p class="text-gray-600 text-sm uppercase tracking-wide mb-2">
                                <i class="fas fa-envelope text-gray-400 mr-1"></i>
                                Email Address
                            </p>
                            <p class="text-lg font-semibold text-gray-900 break-all">{{ $user->email }}</p>
                        </div>

                        <!-- Role -->
                        <div class="pb-6 border-b border-gray-200">
                            <p class="text-gray-600 text-sm uppercase tracking-wide mb-2">
                                <i class="fas fa-crown text-gray-400 mr-1"></i>
                                Role
                            </p>
                            @if ($user->role === 'admin')
                                <span
                                    class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-purple-100 text-purple-800">
                                    <i class="fas fa-crown mr-2"></i>
                                    Administrator
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                    <i class="fas fa-user-check mr-2"></i>
                                    Regular User
                                </span>
                            @endif
                        </div>

                        <!-- Terdaftar -->
                        <div class="pb-6 border-b border-gray-200">
                            <p class="text-gray-600 text-sm uppercase tracking-wide mb-2">
                                <i class="fas fa-calendar-plus text-gray-400 mr-1"></i>
                                Tanggal Terdaftar
                            </p>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $user->created_at->format('d M Y H:i:s') }}
                            </p>
                        </div>

                        <!-- Last Update -->
                        <div>
                            <p class="text-gray-600 text-sm uppercase tracking-wide mb-2">
                                <i class="fas fa-calendar-check text-gray-400 mr-1"></i>
                                Diperbarui
                            </p>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $user->updated_at->format('d M Y H:i:s') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden sticky top-6">
                    <div class="bg-gradient-to-r from-indigo-50 to-blue-50 p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">
                            <i class="fas fa-cog text-indigo-600 mr-2"></i>
                            {{ __('Aksi') }}
                        </h3>
                    </div>

                    <div class="p-6 space-y-3">
                        <a href="{{ route('users.edit', $user->id) }}"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-semibold rounded-lg hover:from-yellow-600 hover:to-orange-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition duration-200">
                            <i class="fas fa-edit"></i>
                            {{ __('Edit') }}
                        </a>

                        <button type="button" id="deleteUserBtn"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-lg hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-200">
                            <i class="fas fa-trash-alt"></i>
                            {{ __('Hapus') }}
                        </button>
                    </div>

                    <!-- Hidden form untuk delete -->
                    <form id="deleteUserForm" method="POST" action="{{ route('users.destroy', $user->id) }}"
                        class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>

                    <!-- Info Box -->
                    <div class="p-4 bg-blue-50 border-t border-gray-200">
                        <p class="text-xs text-blue-600 leading-relaxed">
                            <i class="fas fa-info-circle mr-1"></i>
                            Status aktif: <strong>Terdaftar</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <!-- Load SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            // Delete button handler with SweetAlert
            const deleteBtn = document.getElementById('deleteUserBtn');
            const isSelf = {{ $user->id === auth()->id() ? 'true' : 'false' }};
            const userName = '{{ $user->name }}';
            const userEmail = '{{ $user->email }}';
            const userRole = '{{ $user->role }}';

            if (deleteBtn) {
                deleteBtn.addEventListener('click', function(e) {
                    e.preventDefault();

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
                                <div class="flex items-start">
                                    <i class="fas fa-exclamation-triangle text-red-600 mr-2 mt-0.5"></i>
                                    <div>
                                        <p class="text-sm text-red-700 font-semibold">
                                            PERINGATAN PENTING!
                                        </p>
                                        <p class="text-sm text-red-600 mt-1">
                                            Anda sedang mencoba menghapus akun Anda sendiri (akun yang sedang aktif).
                                        </p>
                                        <p class="text-sm text-red-600 mt-1 font-semibold">
                                            Tindakan ini akan:
                                        </p>
                                        <ul class="text-sm text-red-600 mt-1 list-disc list-inside">
                                            <li>Menghapus akun Anda secara permanen</li>
                                            <li>Menghapus semua data yang terkait dengan akun Anda</li>
                                            <li>Mengeluarkan Anda dari sistem</li>
                                            <li>Tidak dapat dikembalikan!</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        `;
                    } else {
                        warningHtml += `
                            <div class="bg-red-50 border-l-4 border-red-400 p-3 mt-2">
                                <div class="flex items-start">
                                    <i class="fas fa-exclamation-triangle text-red-600 mr-2 mt-0.5"></i>
                                    <div>
                                        <p class="text-sm text-red-700 font-semibold">
                                            PERINGATAN!
                                        </p>
                                        <p class="text-sm text-red-600 mt-1">
                                            Data user akan dihapus secara permanen dari sistem dan tidak dapat dikembalikan!
                                        </p>
                                    </div>
                                </div>
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
                        confirmButtonText: isSelf ? 'Ya, Hapus Akun Saya!' : 'Ya, Hapus User!',
                        cancelButtonText: 'Batal',
                        showLoaderOnConfirm: true,
                        allowOutsideClick: () => !Swal.isLoading(),
                        preConfirm: async () => {
                            try {
                                const form = document.getElementById('deleteUserForm');
                                form.submit();
                                return true;
                            } catch (error) {
                                Swal.showValidationMessage(`Request failed: ${error}`);
                            }
                        }
                    });
                });
            }

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
