@extends('layouts.app')

@section('title', 'Manajemen Grouping Request')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-bold">Manajemen Satu Kelaskan</h1>
        <p class="text-gray-600">Kelola request siswa yang ingin satu kelas</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <a href="{{ route('groupings.index', ['status' => 'pending']) }}"
            class="bg-white rounded-lg shadow p-4 hover:shadow-md transition {{ request('status') == 'pending' ? 'ring-2 ring-yellow-500' : '' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Pending</p>
                    <p class="text-2xl font-bold">{{ $stats['pending'] }}</p>
                </div>
                <i class="fas fa-clock text-yellow-500 text-2xl"></i>
            </div>
        </a>

        <a href="{{ route('groupings.index', ['status' => 'approved']) }}"
            class="bg-white rounded-lg shadow p-4 hover:shadow-md transition {{ request('status') == 'approved' ? 'ring-2 ring-green-500' : '' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Approved</p>
                    <p class="text-2xl font-bold">{{ $stats['approved'] }}</p>
                </div>
                <i class="fas fa-check-circle text-green-500 text-2xl"></i>
            </div>
        </a>

        <a href="{{ route('groupings.index', ['status' => 'rejected']) }}"
            class="bg-white rounded-lg shadow p-4 hover:shadow-md transition {{ request('status') == 'rejected' ? 'ring-2 ring-red-500' : '' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Rejected</p>
                    <p class="text-2xl font-bold">{{ $stats['rejected'] }}</p>
                </div>
                <i class="fas fa-times-circle text-red-500 text-2xl"></i>
            </div>
        </a>

        <a href="{{ route('groupings.index', ['status' => 'processing']) }}"
            class="bg-white rounded-lg shadow p-4 hover:shadow-md transition {{ request('status') == 'processing' ? 'ring-2 ring-blue-500' : '' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Processing</p>
                    <p class="text-2xl font-bold">{{ $stats['processing'] }}</p>
                </div>
                <i class="fas fa-spinner text-blue-500 text-2xl"></i>
            </div>
        </a>

        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Grup</p>
                    <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
                </div>
                <i class="fas fa-users text-gray-500 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Kode atau nama grup..."
                    class="w-full border rounded px-3 py-2">
            </div>

            <div class="w-48">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full border rounded px-3 py-2">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing
                    </option>
                </select>
            </div>

            <div class="w-48">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Ajaran</label>
                <select name="tahun" class="w-full border rounded px-3 py-2">
                    <option value="">Semua</option>
                    @foreach ($tahunAjaran as $ta)
                        <option value="{{ $ta->id }}" {{ request('tahun') == $ta->id ? 'selected' : '' }}>
                            {{ $ta->tahun_ajaran }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    <i class="fas fa-search"></i> Filter
                </button>
                <a href="{{ route('groupings.index') }}"
                    class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                    Reset
                </a>
                <a href="{{ route('groupings.create') }}"
                    class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    <i class="fas fa-plus"></i> Buat Manual
                </a>
            </div>
        </form>
    </div>

    <!-- Bulk Actions -->
    @if ($groupings->count() > 0 && request('status') == 'pending')
        <div class="mb-4 flex gap-2">
            <button onclick="bulkApprove()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                <i class="fas fa-check"></i> Approve Selected
            </button>
            <button onclick="bulkReject()" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                <i class="fas fa-times"></i> Reject Selected
            </button>
        </div>
    @endif

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    @if (request('status') == 'pending')
                        <th class="px-4 py-3">
                            <input type="checkbox" id="selectAll" class="rounded">
                        </th>
                    @endif
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Grup</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Siswa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun Ajaran</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dibuat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($groupings as $group)
                    <tr class="hover:bg-gray-50">
                        @if (request('status') == 'pending')
                            <td class="px-4 py-4">
                                <input type="checkbox" class="select-item rounded" value="{{ $group->id }}">
                            </td>
                        @endif
                        <td class="px-6 py-4 font-mono text-sm">{{ $group->request_code }}</td>
                        <td class="px-6 py-4">
                            <div class="font-medium">{{ $group->group_name }}</div>
                            @if ($group->notes)
                                <div class="text-xs text-gray-500 mt-1">{{ Str::limit($group->notes, 50) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $group->students->count() }} siswa
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">{{ $group->tahunAjaran->tahun_ajaran ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if ($group->status == 'pending')
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i> Pending
                                </span>
                            @elseif($group->status == 'approved')
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i> Disetujui
                                </span>
                            @elseif($group->status == 'rejected')
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-times mr-1"></i> Ditolak
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                    <i class="fas fa-spinner mr-1"></i> Diproses
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            {{ $group->created_at->format('d/m/Y') }}
                            <div class="text-xs text-gray-500">oleh {{ $group->creator->name ?? 'Sistem' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <a href="{{ route('groupings.show', $group->id) }}"
                                    class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if ($group->status == 'pending')
                                    <form action="{{ route('groupings.approve', $group->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-800"
                                            onclick="return confirm('Setujui grouping ini?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('groupings.reject', $group->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:text-red-800"
                                            onclick="return confirm('Tolak grouping ini?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif

                                @if ($group->status != 'approved')
                                    <form action="{{ route('groupings.destroy', $group->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800"
                                            onclick="return confirm('Hapus grouping ini? Siswa akan terlepas dari grup.')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ request('status') == 'pending' ? 8 : 7 }}"
                            class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>Tidak ada data grouping request</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $groupings->appends(request()->query())->links() }}
    </div>

    @push('scripts')
        <script>
            let selectedIds = [];

            document.getElementById('selectAll')?.addEventListener('change', function() {
                document.querySelectorAll('.select-item').forEach(cb => {
                    cb.checked = this.checked;
                });
                updateSelectedIds();
            });

            document.querySelectorAll('.select-item').forEach(cb => {
                cb.addEventListener('change', updateSelectedIds);
            });

            function updateSelectedIds() {
                selectedIds = [];
                document.querySelectorAll('.select-item:checked').forEach(cb => {
                    selectedIds.push(cb.value);
                });
            }

            function bulkApprove() {
                if (selectedIds.length === 0) {
                    alert('Pilih minimal satu grouping request');
                    return;
                }

                if (confirm(`Setujui ${selectedIds.length} grouping request?`)) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('groupings.bulk-approve') }}';
                    form.innerHTML = `
            @csrf
        `;
                    selectedIds.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = id;
                        form.appendChild(input);
                    });
                    document.body.appendChild(form);
                    form.submit();
                }
            }

            function bulkReject() {
                if (selectedIds.length === 0) {
                    alert('Pilih minimal satu grouping request');
                    return;
                }

                if (confirm(`Tolak ${selectedIds.length} grouping request?`)) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('groupings.bulk-reject') }}';
                    form.innerHTML = `
            @csrf
        `;
                    selectedIds.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = id;
                        form.appendChild(input);
                    });
                    document.body.appendChild(form);
                    form.submit();
                }
            }
        </script>
    @endpush

@endsection
