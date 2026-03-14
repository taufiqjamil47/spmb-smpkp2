@extends('layouts.app')

@section('title', 'Tambah Tahun Ajaran')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-bold">Tambah Tahun Ajaran Baru</h1>
        <p class="text-gray-600">Atur kuota pendaftaran untuk tahun ajaran baru</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <form action="{{ route('tahun-ajaran.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Ajaran</label>
                <input type="text" name="tahun_ajaran" value="{{ old('tahun_ajaran') }}" placeholder="Contoh: 2024/2025"
                    class="w-full border rounded px-3 py-2 @error('tahun_ajaran') border-red-500 @enderror" required>
                <p class="text-xs text-gray-500 mt-1">Gunakan format: YYYY/YYYY (contoh: 2024/2025)</p>
                @error('tahun_ajaran')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Kuota Pendaftaran</label>
                <input type="number" name="kuota" value="{{ old('kuota') }}" min="1"
                    class="w-full border rounded px-3 py-2 @error('kuota') border-red-500 @enderror" required>
                @error('kuota')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <div class="flex items-center space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="status" value="aktif" class="form-radio"
                            {{ old('status') == 'aktif' ? 'checked' : '' }}>
                        <span class="ml-2">Aktif</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="status" value="tidak_aktif" class="form-radio"
                            {{ old('status') == 'tidak_aktif' ? 'checked' : '' }} checked>
                        <span class="ml-2">Tidak Aktif</span>
                    </label>
                </div>
                <p class="text-xs text-gray-500 mt-1">Hanya satu tahun ajaran yang boleh aktif</p>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('tahun-ajaran.index') }}"
                    class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                    Batal
                </a>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection
