@extends('layouts.app')

@section('title', 'Pendaftaran Baru')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-bold">Form Pendaftaran Siswa Baru</h1>
        <p class="text-gray-600">Tahun Ajaran {{ $tahunAjaranAktif->tahun_ajaran }} (Sisa Kuota:
            {{ $tahunAjaranAktif->kuota - \App\Models\CalonSiswa::where('tahun_ajaran_id', $tahunAjaranAktif->id)->count() }})
        </p>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('pendaftaran.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Data Pribadi -->
                <div class="col-span-2">
                    <h2 class="text-xl font-semibold mb-4">Data Pribadi Calon Siswa</h2>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}"
                        class="w-full border rounded px-3 py-2" required>
                    @error('nama_lengkap')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">NISN</label>
                    <input type="text" name="nisn" value="{{ old('nisn') }}" class="w-full border rounded px-3 py-2"
                        required>
                    @error('nisn')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}"
                        class="w-full border rounded px-3 py-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                        class="w-full border rounded px-3 py-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="w-full border rounded px-3 py-2" required>
                        <option value="">Pilih</option>
                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Agama</label>
                    <select name="agama" class="w-full border rounded px-3 py-2" required>
                        <option value="">Pilih</option>
                        <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                        <option value="Kristen" {{ old('agama') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                        <option value="Katolik" {{ old('agama') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                        <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                        <option value="Buddha" {{ old('agama') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                        <option value="Konghucu" {{ old('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                    </select>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap</label>
                    <textarea name="alamat" rows="3" class="w-full border rounded px-3 py-2" required>{{ old('alamat') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">No. HP Siswa</label>
                    <input type="text" name="no_hp_siswa" value="{{ old('no_hp_siswa') }}"
                        class="w-full border rounded px-3 py-2" required>
                </div>

                <!-- Data Sekolah -->
                <div class="col-span-2 mt-4">
                    <h2 class="text-xl font-semibold mb-4">Data Asal Sekolah</h2>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Sekolah Asal</label>
                    <input type="text" name="sekolah_asal" value="{{ old('sekolah_asal') }}"
                        class="w-full border rounded px-3 py-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Lulus</label>
                    <select name="tahun_lulus" class="w-full border rounded px-3 py-2" required>
                        <option value="">Pilih</option>
                        @for ($tahun = date('Y'); $tahun >= date('Y') - 5; $tahun--)
                            <option value="{{ $tahun }}" {{ old('tahun_lulus') == $tahun ? 'selected' : '' }}>
                                {{ $tahun }}</option>
                        @endfor
                    </select>
                </div>

                <!-- Data Orang Tua -->
                <div class="col-span-2 mt-4">
                    <h2 class="text-xl font-semibold mb-4">Data Orang Tua/Wali</h2>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Ayah</label>
                    <input type="text" name="nama_ayah" value="{{ old('nama_ayah') }}"
                        class="w-full border rounded px-3 py-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Ibu</label>
                    <input type="text" name="nama_ibu" value="{{ old('nama_ibu') }}"
                        class="w-full border rounded px-3 py-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan Orang Tua</label>
                    <input type="text" name="pekerjaan_ortu" value="{{ old('pekerjaan_ortu') }}"
                        class="w-full border rounded px-3 py-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">No. HP Orang Tua</label>
                    <input type="text" name="no_hp_ortu" value="{{ old('no_hp_ortu') }}"
                        class="w-full border rounded px-3 py-2" required>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                    Simpan Pendaftaran
                </button>
            </div>
        </form>
    </div>
@endsection
