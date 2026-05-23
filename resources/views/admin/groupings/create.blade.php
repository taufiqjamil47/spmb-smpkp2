@extends('layouts.app')

@section('title', 'Buat Grouping Manual')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-bold">Buat Grouping Manual</h1>
        <p class="text-gray-600">Tahun Ajaran: {{ $tahunAjaranAktif->tahun_ajaran }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('groupings.store') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Grup <span
                        class="text-red-500">*</span></label>
                <input type="text" name="group_name" value="{{ old('group_name') }}"
                    class="w-full md:w-1/2 border rounded px-3 py-2 @error('group_name') border-red-500 @enderror"
                    placeholder="Contoh: Grup A - SDN 01" required>
                @error('group_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Siswa <span
                        class="text-red-500">*</span></label>
                <p class="text-sm text-gray-500 mb-2">Pilih minimal 2 siswa untuk ditempatkan dalam satu grup</p>

                <!-- Siswa yang punya request -->
                @if ($studentsWithRequest->count() > 0)
                    <div class="mb-4">
                        <div class="bg-yellow-50 border border-yellow-200 rounded p-3 mb-3">
                            <p class="text-sm font-semibold text-yellow-800">
                                <i class="fas fa-info-circle"></i> Siswa dengan Request Satu Kelas
                            </p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach ($studentsWithRequest as $student)
                                <label class="flex items-center p-3 border rounded hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" name="student_ids[]" value="{{ $student->id }}"
                                        class="rounded mr-3 student-checkbox">
                                    <div>
                                        <div class="font-medium">{{ $student->nama_lengkap }}</div>
                                        <div class="text-xs text-gray-500">
                                            No: {{ $student->no_peserta }} | Request:
                                            {{ $student->formatted_requested_names }}
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Siswa tanpa request -->
                @if ($studentsWithoutRequest->count() > 0)
                    <div>
                        <div class="bg-gray-50 border border-gray-200 rounded p-3 mb-3">
                            <p class="text-sm font-semibold text-gray-700">
                                <i class="fas fa-user"></i> Siswa Lainnya
                            </p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach ($studentsWithoutRequest as $student)
                                <label class="flex items-center p-3 border rounded hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" name="student_ids[]" value="{{ $student->id }}"
                                        class="rounded mr-3 student-checkbox">
                                    <div>
                                        <div class="font-medium">{{ $student->nama_lengkap }}</div>
                                        <div class="text-xs text-gray-500">No: {{ $student->no_peserta }}</div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($studentsWithRequest->count() == 0 && $studentsWithoutRequest->count() == 0)
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-info-circle text-4xl mb-2"></i>
                        <p>Tidak ada siswa yang tersedia untuk dibuat grouping</p>
                    </div>
                @endif

                @error('student_ids')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                <textarea name="notes" rows="3" class="w-full md:w-1/2 border rounded px-3 py-2"
                    placeholder="Tambahkan catatan untuk grouping ini...">{{ old('notes') }}</textarea>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('groupings.index') }}"
                    class="bg-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-400">
                    Batal
                </a>
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                    Simpan Grouping
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.querySelectorAll('.student-checkbox').forEach(cb => {
                cb.addEventListener('change', function() {
                    let checked = document.querySelectorAll('.student-checkbox:checked').length;
                    if (checked < 2 && this.checked) {
                        // Boleh pilih
                    }
                });
            });
        </script>
    @endpush
@endsection
