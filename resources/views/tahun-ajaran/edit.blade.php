@extends('layouts.app')

@section('title', 'Edit Tahun Ajaran')

@section('content')
    <div class="div mt-8">
        <!-- Header Section with Gradient -->
        <div
            class="mb-8 bg-gradient-to-r from-yellow-600 to-orange-600 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden">
            {{-- <div class="absolute top-0 right-0 opacity-10">
                <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L15 8.5L22 9.5L17 14L18.5 21L12 17.5L5.5 21L7 14L2 9.5L9 8.5L12 2Z" />
                </svg>
            </div> --}}
            <div class="relative z-10">
                <div class="flex justify-between items-center flex-wrap gap-4">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Edit Tahun Ajaran</h1>
                        <p class="text-yellow-100">Ubah data tahun ajaran dan kuota</p>
                    </div>
                    <a href="{{ route('tahun-ajaran.index') }}"
                        class="bg-white text-yellow-600 px-5 py-2.5 rounded-xl hover:bg-gray-100 transition-all font-medium shadow-md hover:shadow-lg flex items-center">
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

                <form action="{{ route('tahun-ajaran.update', $tahunAjaran->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Tahun Ajaran Field -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt text-yellow-500 mr-2"></i>
                            Tahun Ajaran <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fas fa-calendar absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="text" name="tahun_ajaran"
                                value="{{ old('tahun_ajaran', $tahunAjaran->tahun_ajaran) }}"
                                placeholder="Contoh: 2024/2025"
                                class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-3 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-200 transition-all @error('tahun_ajaran') border-red-500 @enderror"
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
                            <i class="fas fa-ticket-alt text-yellow-500 mr-2"></i>
                            Kuota Pendaftaran <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <i class="fas fa-users absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="number" name="kuota" value="{{ old('kuota', $tahunAjaran->kuota) }}"
                                min="1"
                                class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-3 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-200 transition-all @error('kuota') border-red-500 @enderror"
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
                            <i class="fas fa-flag-checkered text-yellow-500 mr-2"></i>
                            Status
                        </label>
                        <div class="flex flex-wrap gap-6">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="status" value="aktif"
                                    class="form-radio w-4 h-4 text-yellow-600 focus:ring-yellow-500"
                                    {{ old('status', $tahunAjaran->status) == 'aktif' ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">
                                    <i class="fas fa-check-circle text-green-500 mr-1"></i>
                                    Aktif
                                </span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="status" value="tidak_aktif"
                                    class="form-radio w-4 h-4 text-gray-600"
                                    {{ old('status', $tahunAjaran->status) == 'tidak_aktif' ? 'checked' : '' }}>
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
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Card -->
                    <div class="mb-8 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl p-5 border border-yellow-200">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-10 h-10 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-chart-pie text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="font-bold text-gray-800 mb-3">Statistik Tahun Ajaran</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-white rounded-lg p-3">
                                        <p class="text-xs text-gray-500">Total Pendaftar</p>
                                        <p class="text-xl font-bold text-gray-800">
                                            {{ number_format($tahunAjaran->calon_siswa_count) }} siswa
                                        </p>
                                    </div>
                                    <div class="bg-white rounded-lg p-3">
                                        <p class="text-xs text-gray-500">Sisa Kuota</p>
                                        <p
                                            class="text-xl font-bold {{ $tahunAjaran->kuota - $tahunAjaran->calon_siswa_count < 10 ? 'text-red-600' : 'text-green-600' }}">
                                            {{ number_format($tahunAjaran->kuota - $tahunAjaran->calon_siswa_count) }}
                                        </p>
                                    </div>
                                </div>
                                @if ($tahunAjaran->status == 'aktif')
                                    <div class="mt-3 flex items-center">
                                        <i class="fas fa-bell text-yellow-600 mr-2"></i>
                                        <span class="text-xs text-yellow-700 font-medium">
                                            <i class="fas fa-star mr-1"></i>Tahun ajaran ini sedang AKTIF
                                        </span>
                                    </div>
                                @endif
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
                            class="px-6 py-2.5 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-xl hover:shadow-lg transition-all font-medium">
                            <i class="fas fa-save mr-2"></i>
                            Update Tahun Ajaran
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Warning Note for Edit -->
        @if ($tahunAjaran->calon_siswa_count > 0)
            <div class="mt-6 max-w-3xl mx-auto">
                <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-xl p-4 border border-yellow-300">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5 mr-3 text-lg"></i>
                        <div>
                            <h4 class="font-semibold text-yellow-800 text-sm">Perhatian!</h4>
                            <p class="text-xs text-yellow-700 mt-1">
                                Tahun ajaran ini sudah memiliki {{ number_format($tahunAjaran->calon_siswa_count) }}
                                pendaftar.
                                Perubahan kuota ke bawah dapat mempengaruhi status kuota saat ini.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
