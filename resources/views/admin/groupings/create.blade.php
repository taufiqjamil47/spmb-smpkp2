@extends('layouts.app')

@section('title', 'Buat Grouping Manual')

@section('content')
    <div class="div mt-8">
        <!-- Header Section with Gradient -->
        <div
            class="mb-8 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden">
            {{-- <div class="absolute top-0 right-0 opacity-10">
                <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L15 8.5L22 9.5L17 14L18.5 21L12 17.5L5.5 21L7 14L2 9.5L9 8.5L12 2Z" />
                </svg>
            </div> --}}
            <div class="relative z-10">
                <div class="flex justify-between items-center flex-wrap gap-4">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Buat Grouping Manual</h1>
                        <div class="flex items-center space-x-3">
                            <p class="text-purple-100">Kelompokkan siswa yang ingin satu kelas</p>
                            <span class="px-3 py-1 bg-white bg-opacity-20 rounded-lg text-sm">
                                <i class="fas fa-calendar-alt mr-1"></i> {{ $tahunAjaranAktif->tahun_ajaran }}
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('groupings.index') }}"
                        class="bg-white text-purple-600 px-5 py-2.5 rounded-xl hover:bg-gray-100 transition-all font-medium shadow-md hover:shadow-lg flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="p-8">
                @if ($errors->any())
                    <div class="mb-6 rounded-xl border-l-4 border-red-500 bg-red-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="font-semibold text-red-800">Ada beberapa kesalahan pada input Anda:</p>
                                <ul class="list-disc list-inside mt-2 text-sm text-red-700">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('groupings.store') }}" method="POST" id="groupingForm">
                    @csrf

                    <!-- Nama Grup -->
                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-tag text-purple-500 mr-2"></i>
                            Nama Grup <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <i
                                class="fas fa-layer-group absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="text" name="group_name" value="{{ old('group_name') }}"
                                class="w-full md:w-1/2 border border-gray-200 rounded-xl pl-10 pr-4 py-3 focus:border-purple-400 focus:ring-2 focus:ring-purple-200 transition-all @error('group_name') border-red-500 @enderror"
                                placeholder="Contoh: Grup A - SDN 01" required>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Beri nama grup yang mudah diidentifikasi
                        </p>
                        @error('group_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pilih Siswa -->
                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user-friends text-purple-500 mr-2"></i>
                            Pilih Siswa <span class="text-red-500">*</span>
                        </label>

                        <div class="bg-blue-50 rounded-xl p-4 mb-5 flex items-start">
                            <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
                            <div>
                                <p class="text-sm text-blue-800 font-medium">Informasi Pemilihan Siswa</p>
                                <p class="text-xs text-blue-600 mt-1">Pilih minimal <strong>2 siswa</strong> untuk
                                    ditempatkan dalam satu grup. Anda dapat memilih siswa dari daftar di bawah ini.</p>
                            </div>
                        </div>

                        <!-- Siswa yang punya request -->
                        @if ($studentsWithRequest->count() > 0)
                            <div class="mb-6">
                                <div
                                    class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl p-3 mb-4 border border-yellow-200">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-star text-white text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-yellow-800">
                                                Siswa dengan Request Satu Kelas
                                            </p>
                                            <p class="text-xs text-yellow-600">Siswa-siswa ini memiliki permintaan untuk
                                                satu kelas dengan teman tertentu</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach ($studentsWithRequest as $student)
                                        <label
                                            class="flex items-start p-4 border border-gray-200 rounded-xl hover:bg-yellow-50 cursor-pointer transition-all group">
                                            <input type="checkbox" name="student_ids[]" value="{{ $student->id }}"
                                                class="student-checkbox rounded border-gray-300 text-purple-600 focus:ring-purple-500 mt-1 mr-3">
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-900 group-hover:text-yellow-800">
                                                    {{ $student->nama_lengkap }}</div>
                                                <div class="text-xs text-gray-500 mt-1">
                                                    <span class="font-mono">No: {{ $student->no_peserta }}</span>
                                                    <span class="mx-1">•</span>
                                                    <span class="text-yellow-600">
                                                        <i class="fas fa-handshake mr-1"></i>
                                                        Request: {{ $student->formatted_requested_names }}
                                                    </span>
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
                                <div
                                    class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-3 mb-4 border border-gray-200">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gray-500 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-user text-white text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-700">
                                                Siswa Lainnya
                                            </p>
                                            <p class="text-xs text-gray-500">Siswa tanpa permintaan khusus</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach ($studentsWithoutRequest as $student)
                                        <label
                                            class="flex items-start p-4 border border-gray-200 rounded-xl hover:bg-purple-50 cursor-pointer transition-all group">
                                            <input type="checkbox" name="student_ids[]" value="{{ $student->id }}"
                                                class="student-checkbox rounded border-gray-300 text-purple-600 focus:ring-purple-500 mt-1 mr-3">
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-900 group-hover:text-purple-700">
                                                    {{ $student->nama_lengkap }}</div>
                                                <div class="text-xs text-gray-500 mt-1">
                                                    <span class="font-mono">No: {{ $student->no_peserta }}</span>
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($studentsWithRequest->count() == 0 && $studentsWithoutRequest->count() == 0)
                            <div class="text-center py-12 bg-gray-50 rounded-xl">
                                <div
                                    class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-user-slash text-gray-400 text-3xl"></i>
                                </div>
                                <p class="text-gray-500 font-medium">Tidak ada siswa yang tersedia</p>
                                <p class="text-sm text-gray-400 mt-1">Semua siswa sudah tergabung dalam grup atau tidak ada
                                    data siswa</p>
                            </div>
                        @endif

                        @error('student_ids')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror

                        <!-- Selected Counter -->
                        <div class="mt-4 flex items-center space-x-3" id="selectedCounter" style="display: none;">
                            <span class="text-sm text-gray-600">
                                <i class="fas fa-check-circle text-green-500"></i>
                                Terpilih: <span id="selectedCount">0</span> siswa
                            </span>
                            <span id="warningMinimal" class="text-xs text-yellow-600 hidden">
                                <i class="fas fa-exclamation-triangle"></i> Minimal pilih 2 siswa
                            </span>
                        </div>
                    </div>

                    <!-- Catatan -->
                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-edit text-purple-500 mr-2"></i>
                            Catatan (Opsional)
                        </label>
                        <div class="relative">
                            <textarea name="notes" rows="3"
                                class="w-full md:w-1/2 border border-gray-200 rounded-xl px-4 py-3 focus:border-purple-400 focus:ring-2 focus:ring-purple-200 transition-all"
                                placeholder="Tambahkan catatan untuk grouping ini...">{{ old('notes') }}</textarea>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Catatan hanya akan terlihat oleh admin
                        </p>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('groupings.index') }}"
                            class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all font-medium">
                            <i class="fas fa-times mr-2"></i>
                            Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-2.5 bg-gradient-to-r from-purple-500 to-indigo-500 text-white rounded-xl hover:shadow-lg transition-all font-medium"
                            id="submitBtn">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Grouping
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Update counter for selected students
            function updateSelectedCounter() {
                const checkboxes = document.querySelectorAll('.student-checkbox');
                const checked = document.querySelectorAll('.student-checkbox:checked');
                const count = checked.length;
                const counterDiv = document.getElementById('selectedCounter');
                const selectedCountSpan = document.getElementById('selectedCount');
                const warningSpan = document.getElementById('warningMinimal');
                const submitBtn = document.getElementById('submitBtn');

                if (count > 0) {
                    counterDiv.style.display = 'flex';
                    selectedCountSpan.textContent = count;

                    if (count < 2) {
                        warningSpan.classList.remove('hidden');
                        submitBtn.disabled = true;
                        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                        submitBtn.classList.remove('hover:shadow-lg');
                    } else {
                        warningSpan.classList.add('hidden');
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        submitBtn.classList.add('hover:shadow-lg');
                    }
                } else {
                    counterDiv.style.display = 'none';
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    submitBtn.classList.remove('hover:shadow-lg');
                }
            }

            // Add event listeners to all checkboxes
            document.querySelectorAll('.student-checkbox').forEach(cb => {
                cb.addEventListener('change', updateSelectedCounter);
            });

            // Initial update
            updateSelectedCounter();

            // Form validation before submit
            document.getElementById('groupingForm').addEventListener('submit', function(e) {
                const checked = document.querySelectorAll('.student-checkbox:checked').length;
                if (checked < 2) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Pilih minimal 2 siswa untuk membuat grouping!',
                        confirmButtonColor: '#8B5CF6'
                    });
                }
            });
        </script>
    @endpush
@endsection
