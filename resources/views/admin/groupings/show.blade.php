@extends('layouts.app')

@section('title', 'Detail Grouping Request')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold">Detail Grouping</h1>
            <p class="text-gray-600">Kode: {{ $grouping->request_code }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('groupings.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>

            @if ($grouping->status == 'pending')
                <form action="{{ route('groupings.approve', $grouping->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                        <i class="fas fa-check"></i> Setujui
                    </button>
                </form>

                <form action="{{ route('groupings.reject', $grouping->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                        <i class="fas fa-times"></i> Tolak
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Info Grouping -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4 border-b pb-2">Informasi Grup</h2>

                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-500">Nama Grup</label>
                        <p class="font-medium">{{ $grouping->group_name }}</p>
                    </div>

                    <div>
                        <label class="text-sm text-gray-500">Kode Grup</label>
                        <p class="font-mono text-sm">{{ $grouping->request_code }}</p>
                    </div>

                    <div>
                        <label class="text-sm text-gray-500">Status</label>
                        <div>
                            @if ($grouping->status == 'pending')
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i> Pending
                                </span>
                            @elseif($grouping->status == 'approved')
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i> Disetujui
                                </span>
                            @elseif($grouping->status == 'rejected')
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                    <i class="fas fa-times mr-1"></i> Ditolak
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                    <i class="fas fa-spinner mr-1"></i> Diproses
                                </span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <label class="text-sm text-gray-500">Tahun Ajaran</label>
                        <p>{{ $grouping->tahunAjaran->tahun_ajaran ?? '-' }}</p>
                    </div>

                    <div>
                        <label class="text-sm text-gray-500">Dibuat</label>
                        <p>{{ $grouping->created_at->format('d/m/Y H:i') }} oleh {{ $grouping->creator->name ?? 'Sistem' }}
                        </p>
                    </div>

                    @if ($grouping->approved_at)
                        <div>
                            <label class="text-sm text-gray-500">Disetujui</label>
                            <p>{{ $grouping->approved_at->format('d/m/Y H:i') }} oleh
                                {{ $grouping->approver->name ?? '-' }}</p>
                        </div>
                    @endif

                    @if ($grouping->notes)
                        <div>
                            <label class="text-sm text-gray-500">Catatan</label>
                            <p class="text-sm bg-gray-50 p-2 rounded">{{ $grouping->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tambah Siswa (opsional) -->
            @if ($grouping->status != 'rejected' && isset($availableStudents) && $availableStudents->count() > 0)
                <div class="bg-white rounded-lg shadow p-6 mt-6">
                    <h2 class="text-lg font-semibold mb-4 border-b pb-2">Tambah Siswa</h2>
                    <form action="{{ route('groupings.add-student', $grouping->id) }}" method="POST">
                        @csrf
                        <select name="student_id" class="w-full border rounded px-3 py-2 mb-3" required>
                            <option value="">Pilih siswa...</option>
                            @foreach ($availableStudents as $student)
                                <option value="{{ $student->id }}">{{ $student->nama_lengkap }}
                                    ({{ $student->no_peserta }})</option>
                            @endforeach
                        </select>
                        <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            <i class="fas fa-plus"></i> Tambahkan ke Grup
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <!-- Daftar Siswa -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow">
                <div class="border-b px-6 py-4">
                    <h2 class="text-lg font-semibold">
                        <i class="fas fa-users mr-2"></i>
                        Daftar Siswa dalam Grup ({{ $grouping->students->count() }})
                    </h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Peserta</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Lengkap
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NISN</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prioritas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($grouping->students as $student)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-mono text-sm">{{ $student->no_peserta }}</td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium">{{ $student->nama_lengkap }}</div>
                                        <div class="text-xs text-gray-500">{{ $student->tempat_lahir }},
                                            {{ \Carbon\Carbon::parse($student->tanggal_lahir)->format('d/m/Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4">{{ $student->nisn }}</td>
                                    <td class="px-6 py-4">
                                        @if ($student->grouping_priority == 'high')
                                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Prioritas
                                                Tinggi</span>
                                        @elseif($student->grouping_priority == 'medium')
                                            <span
                                                class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Prioritas
                                                Sedang</span>
                                        @else
                                            <span
                                                class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Normal</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('pendaftaran.show', $student->id) }}"
                                            class="text-blue-600 hover:text-blue-800 mr-2">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if ($grouping->status != 'approved')
                                            <form
                                                action="{{ route('groupings.remove-student', [$grouping->id, $student->id]) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800"
                                                    onclick="return confirm('Keluarkan siswa dari grup ini?')">
                                                    <i class="fas fa-user-minus"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        <i class="fas fa-users-slash text-4xl mb-2"></i>
                                        <p>Belum ada siswa dalam grup ini</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
