@extends('layouts.app')

@section('title', 'Manajemen Grouping Request')

@section('content')
    <div class="div mt-8">
        <!-- Header Section with Gradient -->
        <div
            class="mb-8 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden">
            <div class="relative z-10">
                <div class="flex justify-between items-center flex-wrap gap-4">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Manajemen Satu Kelaskan</h1>
                        <p class="text-purple-100">Kelola request siswa yang ingin satu kelas</p>
                    </div>
                    <a href="{{ route('groupings.create') }}"
                        class="bg-white text-purple-600 px-5 py-2.5 rounded-xl hover:bg-gray-100 transition-all font-medium shadow-md hover:shadow-lg flex items-center">
                        <i class="fas fa-plus mr-2"></i>Buat Manual
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
            <a href="{{ route('groupings.index', ['status' => 'pending']) }}"
                class="group bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-5 {{ request('status') == 'pending' ? 'ring-2 ring-yellow-500 shadow-lg' : '' }}">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Pending</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['pending'] }}</p>
                    </div>
                    <div
                        class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center group-hover:bg-yellow-200 transition">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </a>

            <a href="{{ route('groupings.index', ['status' => 'approved']) }}"
                class="group bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-5 {{ request('status') == 'approved' ? 'ring-2 ring-green-500 shadow-lg' : '' }}">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Approved</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['approved'] }}</p>
                    </div>
                    <div
                        class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center group-hover:bg-green-200 transition">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </a>

            <a href="{{ route('groupings.index', ['status' => 'rejected']) }}"
                class="group bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-5 {{ request('status') == 'rejected' ? 'ring-2 ring-red-500 shadow-lg' : '' }}">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Rejected</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['rejected'] }}</p>
                    </div>
                    <div
                        class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center group-hover:bg-red-200 transition">
                        <i class="fas fa-times-circle text-red-600 text-xl"></i>
                    </div>
                </div>
            </a>

            <a href="{{ route('groupings.index', ['status' => 'processing']) }}"
                class="group bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-5 {{ request('status') == 'processing' ? 'ring-2 ring-blue-500 shadow-lg' : '' }}">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Processing</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['processing'] }}</p>
                    </div>
                    <div
                        class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center group-hover:bg-blue-200 transition">
                        <i class="fas fa-spinner text-blue-600 text-xl"></i>
                    </div>
                </div>
            </a>

            <div class="group bg-gradient-to-r from-purple-50 to-indigo-50 rounded-2xl shadow-md p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-700 text-sm font-medium">Total Grup</p>
                        <p class="text-2xl font-bold text-purple-800 mt-1">{{ $stats['total'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-200 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="bg-white rounded-2xl shadow-xl p-5 mb-8">
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                        <i class="fas fa-search mr-1"></i> Cari
                    </label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari kode atau nama grup..."
                            class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:border-purple-400 focus:ring-2 focus:ring-purple-200 transition-all">
                    </div>
                </div>

                <div class="w-48">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                        <i class="fas fa-filter mr-1"></i> Status
                    </label>
                    <div class="relative">
                        <i class="fas fa-tag absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <select name="status"
                            class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:border-purple-400 focus:ring-2 focus:ring-purple-200 transition-all appearance-none bg-white cursor-pointer">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved
                            </option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected
                            </option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing
                            </option>
                        </select>
                        <i
                            class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>

                <div class="w-56">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                        <i class="fas fa-calendar-alt mr-1"></i> Tahun Ajaran
                    </label>
                    <div class="relative">
                        <i class="fas fa-calendar absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <select name="tahun"
                            class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:border-purple-400 focus:ring-2 focus:ring-purple-200 transition-all appearance-none bg-white cursor-pointer">
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

                <div class="flex gap-2">
                    <button type="submit"
                        class="bg-gradient-to-r from-purple-500 to-indigo-500 text-white px-6 py-2.5 rounded-xl hover:shadow-lg transition-all font-medium">
                        <i class="fas fa-search mr-2"></i> Filter
                    </button>
                    <a href="{{ route('groupings.index') }}"
                        class="bg-gray-100 text-gray-700 px-5 py-2.5 rounded-xl hover:bg-gray-200 transition-all font-medium">
                        <i class="fas fa-undo mr-2"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Bulk Actions -->
        @if ($groupings->count() > 0 && request('status') == 'pending')
            <div class="mb-5 flex gap-3">
                <button type="button" id="bulkApproveBtn"
                    class="bg-gradient-to-r from-green-500 to-green-600 text-white px-5 py-2.5 rounded-xl hover:shadow-lg transition-all font-medium">
                    <i class="fas fa-check mr-2"></i> Approve Selected
                </button>
                <button type="button" id="bulkRejectBtn"
                    class="bg-gradient-to-r from-red-500 to-red-600 text-white px-5 py-2.5 rounded-xl hover:shadow-lg transition-all font-medium">
                    <i class="fas fa-times mr-2"></i> Reject Selected
                </button>
            </div>
        @endif

        <!-- Table -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                            @if (request('status') == 'pending')
                                <th class="px-4 py-4">
                                    <input type="checkbox" id="selectAll"
                                        class="rounded border-gray-300 text-purple-600 focus:ring-purple-500 w-4 h-4">
                                </th>
                            @endif
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Kode</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Nama Grup</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Jumlah Siswa</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Tahun Ajaran</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Dibuat</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($groupings as $group)
                            <tr class="hover:bg-purple-50 transition-colors group" data-group-id="{{ $group->id }}">
                                @if (request('status') == 'pending')
                                    <td class="px-4 py-4">
                                        <input type="checkbox"
                                            class="select-item rounded border-gray-300 text-purple-600 focus:ring-purple-500 w-4 h-4"
                                            value="{{ $group->id }}">
                                    </td>
                                @endif
                                <td class="px-6 py-4">
                                    <span
                                        class="font-mono text-sm font-bold text-purple-600 bg-purple-100 px-2 py-1 rounded-lg">{{ $group->request_code }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900">{{ $group->group_name }}</div>
                                    @if ($group->notes)
                                        <div class="text-xs text-gray-500 mt-1">{{ Str::limit($group->notes, 50) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                        <i class="fas fa-user mr-1"></i> {{ $group->students->count() }} siswa
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $group->tahunAjaran->tahun_ajaran ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($group->status == 'pending')
                                        <span
                                            class="px-2.5 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i> Pending
                                        </span>
                                    @elseif($group->status == 'approved')
                                        <span
                                            class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i> Disetujui
                                        </span>
                                    @elseif($group->status == 'rejected')
                                        <span
                                            class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            <i class="fas fa-times mr-1"></i> Ditolak
                                        </span>
                                    @else
                                        <span
                                            class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            <i class="fas fa-spinner mr-1"></i> Diproses
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm text-gray-700">{{ $group->created_at->format('d/m/Y') }}</span>
                                        <span class="text-xs text-gray-400">oleh
                                            {{ $group->creator->name ?? 'Sistem' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-1.5">
                                        <a href="{{ route('groupings.show', $group->id) }}"
                                            class="text-blue-600 hover:text-white hover:bg-blue-600 p-2 rounded-lg transition-all"
                                            title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if ($group->status == 'pending')
                                            <button type="button"
                                                class="approve-btn text-green-600 hover:text-white hover:bg-green-600 p-2 rounded-lg transition-all"
                                                data-id="{{ $group->id }}" data-code="{{ $group->request_code }}"
                                                title="Setujui">
                                                <i class="fas fa-check"></i>
                                            </button>

                                            <button type="button"
                                                class="reject-btn text-red-600 hover:text-white hover:bg-red-600 p-2 rounded-lg transition-all"
                                                data-id="{{ $group->id }}" data-code="{{ $group->request_code }}"
                                                title="Tolak">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif

                                        @if ($group->status != 'approved')
                                            <button type="button"
                                                class="delete-btn text-red-600 hover:text-white hover:bg-red-600 p-2 rounded-lg transition-all"
                                                data-id="{{ $group->id }}" data-name="{{ $group->group_name }}"
                                                title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>

                                    <!-- Hidden forms untuk aksi -->
                                    <form id="approve-form-{{ $group->id }}"
                                        action="{{ route('groupings.approve', $group->id) }}" method="POST"
                                        class="hidden">
                                        @csrf
                                    </form>

                                    <form id="reject-form-{{ $group->id }}"
                                        action="{{ route('groupings.reject', $group->id) }}" method="POST"
                                        class="hidden">
                                        @csrf
                                    </form>

                                    <form id="delete-form-{{ $group->id }}"
                                        action="{{ route('groupings.destroy', $group->id) }}" method="POST"
                                        class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ request('status') == 'pending' ? 8 : 7 }}"
                                    class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div
                                            class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-inbox text-4xl text-gray-400"></i>
                                        </div>
                                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak Ada Data Grouping
                                            Request</h3>
                                        <p class="text-gray-500">Belum ada request grouping yang diajukan</p>
                                        <a href="{{ route('groupings.create') }}"
                                            class="inline-flex items-center mt-6 bg-gradient-to-r from-purple-500 to-indigo-500 text-white px-5 py-2.5 rounded-xl hover:shadow-lg transition-all">
                                            <i class="fas fa-plus mr-2"></i> Buat Grouping Manual
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
        @if ($groupings->hasPages())
            <div class="mt-8">
                <div class="bg-white rounded-xl shadow-md p-4">
                    {{ $groupings->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>

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
                background: linear-gradient(135deg, #8B5CF6, #6366F1);
                color: white;
                border-color: transparent;
            }

            .pagination .page-item:not(.disabled) .page-link:hover {
                background-color: #F3E8FF;
                border-color: #8B5CF6;
                color: #6D28D9;
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
            let selectedIds = [];

            // Select All functionality
            const selectAllCheckbox = document.getElementById('selectAll');
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    document.querySelectorAll('.select-item').forEach(cb => {
                        cb.checked = this.checked;
                    });
                    updateSelectedIds();
                });
            }

            // Individual checkbox change
            document.querySelectorAll('.select-item').forEach(cb => {
                cb.addEventListener('change', updateSelectedIds);
            });

            function updateSelectedIds() {
                selectedIds = [];
                document.querySelectorAll('.select-item:checked').forEach(cb => {
                    selectedIds.push(cb.value);
                });
            }

            // Approve button handlers
            document.querySelectorAll('.approve-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const groupId = this.dataset.id;
                    const groupCode = this.dataset.code;

                    Swal.fire({
                        title: 'Setujui Grouping?',
                        html: `
                            <div class="text-left">
                                <p class="mb-3">Anda akan menyetujui grouping request dengan kode:</p>
                                <p class="font-mono text-lg font-bold text-purple-600 bg-purple-50 p-2 rounded-lg text-center mb-3">
                                    ${groupCode}
                                </p>
                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 mt-2">
                                    <p class="text-sm text-yellow-800">
                                        <i class="fas fa-info-circle"></i> Grouping yang disetujui akan digunakan dalam pembagian kelas.
                                    </p>
                                </div>
                            </div>
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#10B981',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: 'Ya, Setujui!',
                        cancelButtonText: 'Batal',
                        showLoaderOnConfirm: true,
                        preConfirm: async () => {
                            try {
                                const form = document.getElementById(`approve-form-${groupId}`);
                                form.submit();
                                return true;
                            } catch (error) {
                                Swal.showValidationMessage(`Request failed: ${error}`);
                            }
                        }
                    });
                });
            });

            // Reject button handlers
            document.querySelectorAll('.reject-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const groupId = this.dataset.id;
                    const groupCode = this.dataset.code;

                    Swal.fire({
                        title: 'Tolak Grouping?',
                        html: `
                            <div class="text-left">
                                <p class="mb-3">Anda akan menolak grouping request dengan kode:</p>
                                <p class="font-mono text-lg font-bold text-red-600 bg-red-50 p-2 rounded-lg text-center mb-3">
                                    ${groupCode}
                                </p>
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Alasan Penolakan (Opsional):
                                    </label>
                                    <textarea id="rejectReason" class="swal2-textarea" rows="3" 
                                        placeholder="Masukkan alasan penolakan..."></textarea>
                                </div>
                            </div>
                        `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#EF4444',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: 'Ya, Tolak!',
                        cancelButtonText: 'Batal',
                        preConfirm: () => {
                            const reason = document.getElementById('rejectReason').value;
                            if (reason) {
                                const form = document.getElementById(`reject-form-${groupId}`);
                                let input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = 'reason';
                                input.value = reason;
                                form.appendChild(input);
                            }
                            return true;
                        },
                        showLoaderOnConfirm: true,
                        preConfirm: async () => {
                            try {
                                const form = document.getElementById(`reject-form-${groupId}`);
                                form.submit();
                                return true;
                            } catch (error) {
                                Swal.showValidationMessage(`Request failed: ${error}`);
                            }
                        }
                    });
                });
            });

            // Delete button handlers
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const groupId = this.dataset.id;
                    const groupName = this.dataset.name;

                    Swal.fire({
                        title: 'Hapus Grouping?',
                        html: `
                            <div class="text-left">
                                <p class="mb-3">Anda akan menghapus grouping:</p>
                                <p class="font-semibold text-red-600 bg-red-50 p-2 rounded-lg text-center mb-3">
                                    "${groupName}"
                                </p>
                                <div class="bg-red-50 border-l-4 border-red-400 p-3 mt-2">
                                    <p class="text-sm text-red-800">
                                        <i class="fas fa-exclamation-triangle"></i> 
                                        Semua siswa dalam grup akan terlepas dan data akan dihapus permanen!
                                    </p>
                                </div>
                            </div>
                        `,
                        icon: 'error',
                        showCancelButton: true,
                        confirmButtonColor: '#EF4444',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        showLoaderOnConfirm: true,
                        preConfirm: async () => {
                            try {
                                const form = document.getElementById(`delete-form-${groupId}`);
                                form.submit();
                                return true;
                            } catch (error) {
                                Swal.showValidationMessage(`Request failed: ${error}`);
                            }
                        }
                    });
                });
            });

            // Bulk Approve function
            const bulkApproveBtn = document.getElementById('bulkApproveBtn');
            if (bulkApproveBtn) {
                bulkApproveBtn.addEventListener('click', function() {
                    if (selectedIds.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan',
                            text: 'Pilih minimal satu grouping request',
                            confirmButtonColor: '#8B5CF6'
                        });
                        return;
                    }

                    Swal.fire({
                        title: 'Setujui Grouping Massal?',
                        html: `
                            <div class="text-left">
                                <p class="mb-3">Anda akan menyetujui <strong class="text-green-600">${selectedIds.length}</strong> grouping request</p>
                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 mt-2">
                                    <p class="text-sm text-yellow-800">
                                        <i class="fas fa-info-circle"></i> Semua grouping yang disetujui akan digunakan dalam pembagian kelas.
                                    </p>
                                </div>
                            </div>
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#10B981',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: 'Ya, Setujui Semua!',
                        cancelButtonText: 'Batal',
                        showLoaderOnConfirm: true,
                        preConfirm: async () => {
                            try {
                                const form = document.createElement('form');
                                form.method = 'POST';
                                form.action = '{{ route('groupings.bulk-approve') }}';
                                form.innerHTML = `@csrf`;
                                selectedIds.forEach(id => {
                                    const input = document.createElement('input');
                                    input.type = 'hidden';
                                    input.name = 'ids[]';
                                    input.value = id;
                                    form.appendChild(input);
                                });
                                document.body.appendChild(form);
                                form.submit();
                                return true;
                            } catch (error) {
                                Swal.showValidationMessage(`Request failed: ${error}`);
                            }
                        }
                    });
                });
            }

            // Bulk Reject function
            const bulkRejectBtn = document.getElementById('bulkRejectBtn');
            if (bulkRejectBtn) {
                bulkRejectBtn.addEventListener('click', function() {
                    if (selectedIds.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan',
                            text: 'Pilih minimal satu grouping request',
                            confirmButtonColor: '#8B5CF6'
                        });
                        return;
                    }

                    Swal.fire({
                        title: 'Tolak Grouping Massal?',
                        html: `
                            <div class="text-left">
                                <p class="mb-3">Anda akan menolak <strong class="text-red-600">${selectedIds.length}</strong> grouping request</p>
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Alasan Penolakan (Opsional):
                                    </label>
                                    <textarea id="bulkRejectReason" class="swal2-textarea" rows="3" 
                                        placeholder="Masukkan alasan penolakan..."></textarea>
                                </div>
                            </div>
                        `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#EF4444',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: 'Ya, Tolak Semua!',
                        cancelButtonText: 'Batal',
                        preConfirm: () => {
                            const reason = document.getElementById('bulkRejectReason').value;
                            return {
                                reason: reason
                            };
                        },
                        showLoaderOnConfirm: true,
                        preConfirm: async (result) => {
                            try {
                                const form = document.createElement('form');
                                form.method = 'POST';
                                form.action = '{{ route('groupings.bulk-reject') }}';
                                form.innerHTML = `@csrf`;
                                selectedIds.forEach(id => {
                                    const input = document.createElement('input');
                                    input.type = 'hidden';
                                    input.name = 'ids[]';
                                    input.value = id;
                                    form.appendChild(input);
                                });
                                if (result.reason) {
                                    const input = document.createElement('input');
                                    input.type = 'hidden';
                                    input.name = 'reason';
                                    input.value = result.reason;
                                    form.appendChild(input);
                                }
                                document.body.appendChild(form);
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
