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
        @if ($errors->any())
            <div class="mb-6 rounded border border-red-200 bg-red-50 p-4 text-red-700">
                <p class="font-semibold">Ada beberapa kesalahan pada input Anda:</p>
                <ul class="list-disc list-inside mt-2 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('pendaftaran.store') }}" method="POST">
            @csrf

            <!-- Data Pribadi Calon Siswa -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4 pb-2 border-b">A. Data Pribadi Calon Siswa</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}"
                            class="w-full border rounded px-3 py-2 @error('nama_lengkap') border-red-500 @enderror"
                            required>
                        @error('nama_lengkap')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">NISN <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="nisn" value="{{ old('nisn') }}"
                            class="w-full border rounded px-3 py-2 @error('nisn') border-red-500 @enderror" required
                            maxlength="10" minlength="10" pattern="[0-9]{10}" inputmode="numeric"
                            placeholder="10 digit angka">
                        @error('nisn')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">NIK <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="nik" value="{{ old('nik') }}"
                            class="w-full border rounded px-3 py-2 @error('nik') border-red-500 @enderror" required
                            maxlength="16" minlength="16" pattern="[0-9]{16}" inputmode="numeric"
                            placeholder="16 digit angka">
                        @error('nik')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}"
                            class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir <span
                                class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                            class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin <span
                                class="text-red-500">*</span></label>
                        <select name="jenis_kelamin" class="w-full border rounded px-3 py-2" required>
                            <option value="">Pilih</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Agama <span
                                class="text-red-500">*</span></label>
                        <select name="agama" class="w-full border rounded px-3 py-2" required>
                            <option value="">Pilih</option>
                            <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                            <option value="Kristen Protestan" {{ old('agama') == 'Kristen Protestan' ? 'selected' : '' }}>
                                Kristen Protestan</option>
                            <option value="Katholik" {{ old('agama') == 'Katholik' ? 'selected' : '' }}>Katholik</option>
                            <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                            <option value="Buddha" {{ old('agama') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                            <option value="Konghucu" {{ old('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                            <option value="Lainnya" {{ old('agama') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
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
                        <textarea name="alamat" rows="2" class="w-full border rounded px-3 py-2" required>{{ old('alamat') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">RT</label>
                        <input type="number" name="rt" value="{{ old('rt') }}"
                            class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">RW</label>
                        <input type="number" name="rw" value="{{ old('rw') }}"
                            class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Desa/Kelurahan</label>
                        <input type="text" name="desa" value="{{ old('desa') }}"
                            class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kecamatan</label>
                        <input type="text" name="kecamatan" value="{{ old('kecamatan') }}"
                            class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">No. HP Siswa <span
                                class="text-red-500">*</span></label>
                        <input type="tel" name="no_hp_siswa" value="{{ old('no_hp_siswa') }}"
                            class="w-full border rounded px-3 py-2 @error('no_hp_siswa') border-red-500 @enderror" required
                            minlength="9" maxlength="16" pattern="[0-9+ ]{9,16}" inputmode="tel" autocomplete="tel"
                            placeholder="Contoh: 081234567890">
                        @error('no_hp_siswa')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon Rumah <span
                                class="text-red-500">*</span></label>
                        <input type="tel" name="no_telp" value="{{ old('no_telp') }}"
                            class="w-full border rounded px-3 py-2 @error('no_telp') border-red-500 @enderror" required
                            minlength="9" maxlength="16" pattern="[0-9+ ()-]{9,16}" inputmode="tel" autocomplete="tel"
                            placeholder="Contoh: (021) 1234567">
                        @error('no_telp')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
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
                        <input type="text" name="sekolah_asal" value="{{ old('sekolah_asal') }}"
                            class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Lulus <span
                                class="text-red-500">*</span></label>
                        <select name="tahun_lulus" class="w-full border rounded px-3 py-2" required>
                            <option value="">Pilih</option>
                            @for ($tahun = date('Y'); $tahun >= date('Y') - 5; $tahun--)
                                <option value="{{ $tahun }}" {{ old('tahun_lulus') == $tahun ? 'selected' : '' }}>
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
                        <input type="number" name="tinggi_badan" value="{{ old('tinggi_badan') }}"
                            class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Berat Badan (kg)</label>
                        <input type="number" name="berat_badan" value="{{ old('berat_badan') }}"
                            class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Anak Ke-</label>
                        <input type="number" name="anak_ke" value="{{ old('anak_ke') }}"
                            class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ukuran Baju</label>
                        <select name="ukuran_baju" class="w-full border rounded px-3 py-2">
                            <option value="">Pilih</option>
                            <option value="S" {{ old('ukuran_baju') == 'S' ? 'selected' : '' }}>S</option>
                            <option value="M" {{ old('ukuran_baju') == 'M' ? 'selected' : '' }}>M</option>
                            <option value="L" {{ old('ukuran_baju') == 'L' ? 'selected' : '' }}>L</option>
                            <option value="XL" {{ old('ukuran_baju') == 'XL' ? 'selected' : '' }}>XL</option>
                            <option value="XXL" {{ old('ukuran_baju') == 'XXL' ? 'selected' : '' }}>XXL</option>
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
                        <input type="text" name="pkh" value="{{ old('pkh') }}"
                            class="w-full border rounded px-3 py-2" placeholder="Nomor PKH">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">KKS</label>
                        <input type="text" name="kks" value="{{ old('kks') }}"
                            class="w-full border rounded px-3 py-2" placeholder="Nomor KKS">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">PIP</label>
                        <input type="text" name="pip" value="{{ old('pip') }}"
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
                        <input type="text" name="nama_ayah" value="{{ old('nama_ayah') }}"
                            class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Lahir Ayah</label>
                        <input type="text" name="tahun_lahir_ayah" value="{{ old('tahun_lahir_ayah') }}"
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
                                <option value="{{ $p }}" {{ old('pekerjaan_ayah') == $p ? 'selected' : '' }}>
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
                                <option value="{{ $p }}" {{ old('pendidikan_ayah') == $p ? 'selected' : '' }}>
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
                        <input type="text" name="nama_ibu" value="{{ old('nama_ibu') }}"
                            class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Lahir Ibu</label>
                        <input type="text" name="tahun_lahir_ibu" value="{{ old('tahun_lahir_ibu') }}"
                            class="w-full border rounded px-3 py-2" placeholder="Contoh: 1978">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan Ibu</label>
                        <select name="pekerjaan_ibu" class="w-full border rounded px-3 py-2">
                            <option value="">Pilih</option>
                            @foreach ($pekerjaan as $p)
                                <option value="{{ $p }}" {{ old('pekerjaan_ibu') == $p ? 'selected' : '' }}>
                                    {{ $p }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pendidikan Ibu</label>
                        <select name="pendidikan_ibu" class="w-full border rounded px-3 py-2">
                            <option value="">Pilih</option>
                            @foreach ($pendidikan as $p)
                                <option value="{{ $p }}" {{ old('pendidikan_ibu') == $p ? 'selected' : '' }}>
                                    {{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Data Wali (Opsional) -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4 pb-2 border-b">H. Data Wali (Jika Bukan Orang Tua Kandung)</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Wali</label>
                        <input type="text" name="nama_wali" value="{{ old('nama_wali') }}"
                            class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Lahir Wali</label>
                        <input type="text" name="tahun_lahir_wali" value="{{ old('tahun_lahir_wali') }}"
                            class="w-full border rounded px-3 py-2" placeholder="Contoh: 1975">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan Wali</label>
                        <select name="pekerjaan_wali" class="w-full border rounded px-3 py-2">
                            <option value="">Pilih</option>
                            @foreach ($pekerjaan as $p)
                                <option value="{{ $p }}" {{ old('pekerjaan_wali') == $p ? 'selected' : '' }}>
                                    {{ $p }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pendidikan Wali</label>
                        <select name="pendidikan_wali" class="w-full border rounded px-3 py-2">
                            <option value="">Pilih</option>
                            @foreach ($pendidikan as $p)
                                <option value="{{ $p }}" {{ old('pendidikan_wali') == $p ? 'selected' : '' }}>
                                    {{ $p }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">No. HP Orang Tua/Wali <span
                                class="text-red-500">*</span></label>
                        <input type="tel" name="no_hp_ortu" value="{{ old('no_hp_ortu') }}"
                            class="w-full border rounded px-3 py-2 @error('no_hp_ortu') border-red-500 @enderror" required
                            minlength="9" maxlength="16" pattern="[0-9+ ]{9,16}" inputmode="tel" autocomplete="tel"
                            placeholder="Contoh: 081234567890">
                        @error('no_hp_ortu')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- I. Request Satu Kelaskan -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4 pb-2 border-b">I. Request Satu Kelaskan (Opsional)</h2>
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-yellow-600"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                Jika didapati anak ingin ditempatkan satu kelas dengan teman tertentu, silakan isi form di
                                bawah
                                ini.
                                <br>Mohon diisi dengan nama lengkap sesuai data pendaftaran teman yang dimaksud.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Request satu kelas dengan (pisahkan dengan koma)
                        </label>
                        <input type="text" name="requested_with_names_raw"
                            value="{{ old('requested_with_names_raw') }}" class="w-full border rounded px-3 py-2"
                            placeholder="Contoh: Ahmad Fauzi, Siti Nurhaliza, Budi Santoso">
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-info-circle"></i>
                            Masukkan nama lengkap teman yang diinginkan, pisahkan dengan koma.
                            Request ini akan diproses oleh admin saat pembagian kelas.
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Prioritas Request (Optional)
                        </label>
                        <select name="grouping_priority" class="w-full md:w-1/2 border rounded px-3 py-2">
                            <option value="none" {{ old('grouping_priority') == 'none' ? 'selected' : '' }}>Normal
                            </option>
                            <option value="medium" {{ old('grouping_priority') == 'medium' ? 'selected' : '' }}>Prioritas
                                Sedang</option>
                            <option value="high" {{ old('grouping_priority') == 'high' ? 'selected' : '' }}>Prioritas
                                Tinggi</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Prioritas tinggi akan diusahakan lebih dahulu</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('pendaftaran.index') }}"
                    class="bg-gray-300 text-gray-700 px-6 py-2 rounded hover:bg-gray-400">
                    Batal
                </a>
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                    Simpan Pendaftaran
                </button>
            </div>
        </form>
    </div>
@endsection
