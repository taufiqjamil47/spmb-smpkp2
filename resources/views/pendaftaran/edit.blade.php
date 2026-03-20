@extends('layouts.app')

@section('title', 'Edit Data Pendaftar')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-bold">Edit Data Calon Siswa</h1>
        <p class="text-gray-600">Nomor Peserta: {{ $pendaftar->no_peserta }}</p>
        @if ($pendaftar->trashed())
            <div class="mt-2">
                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold">
                    <i class="fas fa-trash-alt mr-1"></i>Data sedang dalam trash
                </span>
            </div>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('pendaftaran.update', $pendaftar->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Info Tahun Ajaran -->
            <div class="mb-6 p-4 bg-blue-50 rounded">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Ajaran <span
                        class="text-red-500">*</span></label>
                <select name="tahun_ajaran_id" class="w-full md:w-1/2 border rounded px-3 py-2" required>
                    @foreach ($tahunAjaran as $ta)
                        <option value="{{ $ta->id }}"
                            {{ old('tahun_ajaran_id', $pendaftar->tahun_ajaran_id) == $ta->id ? 'selected' : '' }}>
                            {{ $ta->tahun_ajaran }} {{ $ta->status == 'aktif' ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Data Pribadi Calon Siswa -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4 pb-2 border-b">A. Data Pribadi Calon Siswa</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="nama_lengkap"
                            value="{{ old('nama_lengkap', $pendaftar->nama_lengkap) }}"
                            class="w-full border rounded px-3 py-2 @error('nama_lengkap') border-red-500 @enderror"
                            required>
                        @error('nama_lengkap')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">NISN <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="nisn" value="{{ old('nisn', $pendaftar->nisn) }}"
                            class="w-full border rounded px-3 py-2 @error('nisn') border-red-500 @enderror" required>
                        @error('nisn')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">NIK <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="nik" value="{{ old('nik', $pendaftar->nik) }}"
                            class="w-full border rounded px-3 py-2 @error('nik') border-red-500 @enderror" required>
                        @error('nik')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="tempat_lahir"
                            value="{{ old('tempat_lahir', $pendaftar->tempat_lahir) }}"
                            class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir <span
                                class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_lahir"
                            value="{{ old('tanggal_lahir', $pendaftar->tanggal_lahir) }}"
                            class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin <span
                                class="text-red-500">*</span></label>
                        <select name="jenis_kelamin" class="w-full border rounded px-3 py-2" required>
                            <option value="">Pilih</option>
                            <option value="L"
                                {{ old('jenis_kelamin', $pendaftar->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki
                            </option>
                            <option value="P"
                                {{ old('jenis_kelamin', $pendaftar->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Agama <span
                                class="text-red-500">*</span></label>
                        <select name="agama" class="w-full border rounded px-3 py-2" required>
                            <option value="">Pilih</option>
                            <option value="Islam" {{ old('agama', $pendaftar->agama) == 'Islam' ? 'selected' : '' }}>Islam
                            </option>
                            <option value="Kristen Protestan"
                                {{ old('agama', $pendaftar->agama) == 'Kristen Protestan' ? 'selected' : '' }}>Kristen
                                Protestan</option>
                            <option value="Katholik" {{ old('agama', $pendaftar->agama) == 'Katholik' ? 'selected' : '' }}>
                                Katholik</option>
                            <option value="Hindu" {{ old('agama', $pendaftar->agama) == 'Hindu' ? 'selected' : '' }}>Hindu
                            </option>
                            <option value="Buddha" {{ old('agama', $pendaftar->agama) == 'Buddha' ? 'selected' : '' }}>
                                Buddha</option>
                            <option value="Konghucu" {{ old('agama', $pendaftar->agama) == 'Konghucu' ? 'selected' : '' }}>
                                Konghucu</option>
                            <option value="Lainnya" {{ old('agama', $pendaftar->agama) == 'Lainnya' ? 'selected' : '' }}>
                                Lainnya</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Alamat Lengkap -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4 pb-2 border-b">B. Alamat Lengkap</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat (Jalan/Dusun) <span
                                class="text-red-500">*</span></label>
                        <textarea name="alamat" rows="2" class="w-full border rounded px-3 py-2" required>{{ old('alamat', $pendaftar->alamat) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">RT</label>
                        <input type="number" name="rt" value="{{ old('rt', $pendaftar->rt) }}"
                            class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">RW</label>
                        <input type="number" name="rw" value="{{ old('rw', $pendaftar->rw) }}"
                            class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Desa/Kelurahan</label>
                        <input type="text" name="desa" value="{{ old('desa', $pendaftar->desa) }}"
                            class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kecamatan</label>
                        <input type="text" name="kecamatan" value="{{ old('kecamatan', $pendaftar->kecamatan) }}"
                            class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">No. HP Siswa</label>
                        <input type="text" name="no_hp_siswa"
                            value="{{ old('no_hp_siswa', $pendaftar->no_hp_siswa) }}"
                            class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon Rumah</label>
                        <input type="text" name="no_telp" value="{{ old('no_telp', $pendaftar->no_telp) }}"
                            class="w-full border rounded px-3 py-2">
                    </div>
                </div>
            </div>

            <!-- Data Sekolah Asal -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4 pb-2 border-b">C. Data Asal Sekolah</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Sekolah Asal <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="sekolah_asal"
                            value="{{ old('sekolah_asal', $pendaftar->sekolah_asal) }}"
                            class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Lulus <span
                                class="text-red-500">*</span></label>
                        <select name="tahun_lulus" class="w-full border rounded px-3 py-2" required>
                            <option value="">Pilih</option>
                            @for ($tahun = date('Y'); $tahun >= date('Y') - 5; $tahun--)
                                <option value="{{ $tahun }}"
                                    {{ old('tahun_lulus', $pendaftar->tahun_lulus) == $tahun ? 'selected' : '' }}>
                                    {{ $tahun }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>

            <!-- Data Kesehatan & Lainnya -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4 pb-2 border-b">D. Data Kesehatan dan Lainnya</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tinggi Badan (cm)</label>
                        <input type="number" name="tinggi_badan"
                            value="{{ old('tinggi_badan', $pendaftar->tinggi_badan) }}"
                            class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Berat Badan (kg)</label>
                        <input type="number" name="berat_badan"
                            value="{{ old('berat_badan', $pendaftar->berat_badan) }}"
                            class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Anak Ke-</label>
                        <input type="number" name="anak_ke" value="{{ old('anak_ke', $pendaftar->anak_ke) }}"
                            class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ukuran Baju</label>
                        <select name="ukuran_baju" class="w-full border rounded px-3 py-2">
                            <option value="">Pilih</option>
                            <option value="S"
                                {{ old('ukuran_baju', $pendaftar->ukuran_baju) == 'S' ? 'selected' : '' }}>S</option>
                            <option value="M"
                                {{ old('ukuran_baju', $pendaftar->ukuran_baju) == 'M' ? 'selected' : '' }}>M</option>
                            <option value="L"
                                {{ old('ukuran_baju', $pendaftar->ukuran_baju) == 'L' ? 'selected' : '' }}>L</option>
                            <option value="XL"
                                {{ old('ukuran_baju', $pendaftar->ukuran_baju) == 'XL' ? 'selected' : '' }}>XL</option>
                            <option value="XXL"
                                {{ old('ukuran_baju', $pendaftar->ukuran_baju) == 'XXL' ? 'selected' : '' }}>XXL</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Data Program Bantuan -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4 pb-2 border-b">E. Program Bantuan (Jika Ada)</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">PKH</label>
                        <input type="text" name="pkh" value="{{ old('pkh', $pendaftar->pkh) }}"
                            class="w-full border rounded px-3 py-2" placeholder="Nomor PKH">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">KKS</label>
                        <input type="text" name="kks" value="{{ old('kks', $pendaftar->kks) }}"
                            class="w-full border rounded px-3 py-2" placeholder="Nomor KKS">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">PIP</label>
                        <input type="text" name="pip" value="{{ old('pip', $pendaftar->pip) }}"
                            class="w-full border rounded px-3 py-2" placeholder="Nomor PIP">
                    </div>
                </div>
            </div>

            <!-- Data Ayah -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4 pb-2 border-b">F. Data Ayah</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Ayah <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="nama_ayah" value="{{ old('nama_ayah', $pendaftar->nama_ayah) }}"
                            class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Lahir Ayah</label>
                        <input type="text" name="tahun_lahir_ayah"
                            value="{{ old('tahun_lahir_ayah', $pendaftar->tahun_lahir_ayah) }}"
                            class="w-full border rounded px-3 py-2" placeholder="Contoh: 1975">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan Ayah</label>
                        <select name="pekerjaan_ayah" class="w-full border rounded px-3 py-2">
                            <option value="">Pilih</option>
                            @php
                                $pekerjaan = [
                                    'Tidak Bekerja',
                                    'Nelayan',
                                    'Petani',
                                    'Peternak',
                                    'PNS/TNI/POLRI',
                                    'Karyawan Swasta',
                                    'Pedagang Kecil',
                                    'Pedagang Besar',
                                    'Wiraswasta',
                                    'Wirausaha',
                                    'Buruh',
                                    'Pensiunan',
                                    'Lainnya',
                                ];
                            @endphp
                            @foreach ($pekerjaan as $p)
                                <option value="{{ $p }}"
                                    {{ old('pekerjaan_ayah', $pendaftar->pekerjaan_ayah) == $p ? 'selected' : '' }}>
                                    {{ $p }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pendidikan Ayah</label>
                        <select name="pendidikan_ayah" class="w-full border rounded px-3 py-2">
                            <option value="">Pilih</option>
                            @php
                                $pendidikan = [
                                    'Tidak Sekolah',
                                    'Putus SD',
                                    'SD Sederajat',
                                    'SMP',
                                    'SMA',
                                    'D1',
                                    'D2',
                                    'D3',
                                    'D4/S1',
                                    'S2',
                                    'S3',
                                ];
                            @endphp
                            @foreach ($pendidikan as $p)
                                <option value="{{ $p }}"
                                    {{ old('pendidikan_ayah', $pendaftar->pendidikan_ayah) == $p ? 'selected' : '' }}>
                                    {{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Data Ibu -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4 pb-2 border-b">G. Data Ibu</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Ibu <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="nama_ibu" value="{{ old('nama_ibu', $pendaftar->nama_ibu) }}"
                            class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Lahir Ibu</label>
                        <input type="text" name="tahun_lahir_ibu"
                            value="{{ old('tahun_lahir_ibu', $pendaftar->tahun_lahir_ibu) }}"
                            class="w-full border rounded px-3 py-2" placeholder="Contoh: 1978">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan Ibu</label>
                        <select name="pekerjaan_ibu" class="w-full border rounded px-3 py-2">
                            <option value="">Pilih</option>
                            @foreach ($pekerjaan as $p)
                                <option value="{{ $p }}"
                                    {{ old('pekerjaan_ibu', $pendaftar->pekerjaan_ibu) == $p ? 'selected' : '' }}>
                                    {{ $p }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pendidikan Ibu</label>
                        <select name="pendidikan_ibu" class="w-full border rounded px-3 py-2">
                            <option value="">Pilih</option>
                            @foreach ($pendidikan as $p)
                                <option value="{{ $p }}"
                                    {{ old('pendidikan_ibu', $pendaftar->pendidikan_ibu) == $p ? 'selected' : '' }}>
                                    {{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Data Wali -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4 pb-2 border-b">H. Data Wali (Jika Bukan Orang Tua Kandung)</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Wali</label>
                        <input type="text" name="nama_wali" value="{{ old('nama_wali', $pendaftar->nama_wali) }}"
                            class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Lahir Wali</label>
                        <input type="text" name="tahun_lahir_wali"
                            value="{{ old('tahun_lahir_wali', $pendaftar->tahun_lahir_wali) }}"
                            class="w-full border rounded px-3 py-2" placeholder="Contoh: 1975">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan Wali</label>
                        <select name="pekerjaan_wali" class="w-full border rounded px-3 py-2">
                            <option value="">Pilih</option>
                            @foreach ($pekerjaan as $p)
                                <option value="{{ $p }}"
                                    {{ old('pekerjaan_wali', $pendaftar->pekerjaan_wali) == $p ? 'selected' : '' }}>
                                    {{ $p }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pendidikan Wali</label>
                        <select name="pendidikan_wali" class="w-full border rounded px-3 py-2">
                            <option value="">Pilih</option>
                            @foreach ($pendidikan as $p)
                                <option value="{{ $p }}"
                                    {{ old('pendidikan_wali', $pendaftar->pendidikan_wali) == $p ? 'selected' : '' }}>
                                    {{ $p }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">No. HP Orang Tua/Wali <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="no_hp_ortu" value="{{ old('no_hp_ortu', $pendaftar->no_hp_ortu) }}"
                            class="w-full border rounded px-3 py-2" required>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('pendaftaran.show', $pendaftar->id) }}"
                    class="bg-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-400">
                    Batal
                </a>
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                    Update Data
                </button>
            </div>
        </form>
    </div>
@endsection
