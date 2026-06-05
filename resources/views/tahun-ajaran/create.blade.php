@extends('layouts.app')

@section('title', 'Tambah Tahun Ajaran')

@section('content')
    <div class="div mt-8">
        <!-- Header Section with Gradient -->
        <div
            class="mb-8 bg-gradient-to-r from-teal-600 to-cyan-600 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden">
            {{-- <div class="absolute top-0 right-0 opacity-10">
                <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L15 8.5L22 9.5L17 14L18.5 21L12 17.5L5.5 21L7 14L2 9.5L9 8.5L12 2Z" />
                </svg>
            </div> --}}
            <div class="relative z-10">
                <div class="flex justify-between items-center flex-wrap gap-4">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Tambah Tahun Ajaran Baru</h1>
                        <p class="text-teal-100">Atur kuota pendaftaran untuk tahun ajaran baru</p>
                    </div>
                    <a href="{{ route('tahun-ajaran.index') }}"
                        class="bg-white text-teal-600 px-5 py-2.5 rounded-xl hover:bg-gray-100 transition-all font-medium shadow-md hover:shadow-lg flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden max-w-3xl mx-auto">
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

                <form action="{{ route('tahun-ajaran.store') }}" method="POST">
                    @csrf

                    <!-- Tahun Ajaran Field -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt text-teal-500 mr-2"></i>
                            Tahun Ajaran <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fas fa-calendar absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="text" name="tahun_ajaran" value="{{ old('tahun_ajaran') }}"
                                placeholder="Contoh: 2024/2025"
                                class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-3 focus:border-teal-400 focus:ring-2 focus:ring-teal-200 transition-all @error('tahun_ajaran') border-red-500 @enderror"
                                required>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Gunakan format: YYYY/YYYY (contoh: 2024/2025)
                        </p>
                        @error('tahun_ajaran')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kuota Field -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-ticket-alt text-teal-500 mr-2"></i>
                            Kuota Pendaftaran <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fas fa-users absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="number" name="kuota" value="{{ old('kuota') }}" min="1"
                                class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-3 focus:border-teal-400 focus:ring-2 focus:ring-teal-200 transition-all @error('kuota') border-red-500 @enderror"
                                required placeholder="Masukkan jumlah kuota">
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Jumlah maksimal siswa yang dapat mendaftar
                        </p>
                        @error('kuota')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Field -->
                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-flag-checkered text-teal-500 mr-2"></i>
                            Status
                        </label>
                        <div class="flex flex-wrap gap-6">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="status" value="aktif"
                                    class="form-radio w-4 h-4 text-teal-600 focus:ring-teal-500"
                                    {{ old('status') == 'aktif' ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">
                                    <i class="fas fa-check-circle text-green-500 mr-1"></i>
                                    Aktif
                                </span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="status" value="tidak_aktif"
                                    class="form-radio w-4 h-4 text-gray-600"
                                    {{ old('status') == 'tidak_aktif' ? 'checked' : '' }} checked>
                                <span class="ml-2 text-gray-700">
                                    <i class="fas fa-circle text-gray-400 mr-1"></i>
                                    Tidak Aktif
                                </span>
                            </label>
                        </div>
                        <div class="mt-3 bg-blue-50 rounded-lg p-3">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-2"></i>
                                <p class="text-xs text-blue-700">
                                    <strong>Informasi:</strong> Hanya satu tahun ajaran yang boleh berstatus
                                    <strong>Aktif</strong>.
                                    Tahun ajaran aktif akan digunakan untuk pendaftaran baru.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('tahun-ajaran.index') }}"
                            class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all font-medium">
                            <i class="fas fa-times mr-2"></i>
                            Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-2.5 bg-gradient-to-r from-teal-500 to-cyan-500 text-white rounded-xl hover:shadow-lg transition-all font-medium">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Tahun Ajaran
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Quick Tips -->
        <div class="mt-8 max-w-3xl mx-auto">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200">
                <div class="flex items-start">
                    <i class="fas fa-lightbulb text-yellow-500 mt-0.5 mr-3 text-lg"></i>
                    <div>
                        <h4 class="font-semibold text-gray-800 text-sm">Tips Pengisian:</h4>
                        <ul class="text-xs text-gray-600 mt-1 space-y-1">
                            <li>• Pastikan format tahun ajaran benar (YYYY/YYYY)</li>
                            <li>• Kuota minimal adalah 1 siswa</li>
                            <li>• Hanya satu tahun ajaran yang dapat diaktifkan</li>
                            <li>• Tahun ajaran aktif akan otomatis menonaktifkan tahun ajaran sebelumnya</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
