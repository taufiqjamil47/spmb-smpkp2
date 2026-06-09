@extends('layouts.app')

@section('title', 'Edit Data Pendaftar')

@section('content')
    @php
        $pendaftaranQuery = request()->only(['page', 'search', 'tahun']);
    @endphp
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
                        <h1 class="text-3xl font-bold mb-2">Edit Data Calon Siswa</h1>
                        <div class="flex items-center space-x-3">
                            <p class="text-yellow-100">Nomor Peserta:
                                <span class="font-mono font-semibold">{{ $pendaftar->no_peserta }}</span>
                            </p>
                            @if ($pendaftar->trashed())
                                <span class="px-3 py-1 bg-red-500 bg-opacity-80 rounded-full text-sm font-semibold">
                                    <i class="fas fa-trash-alt mr-1"></i>Data dalam Trash
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('pendaftaran.show', $pendaftar->id) }}{{ !empty($pendaftaranQuery) ? '?' . http_build_query($pendaftaranQuery) : '' }}"
                            class="bg-white text-yellow-600 px-4 py-2 rounded-xl hover:bg-gray-100 transition-all font-medium">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            @if ($errors->any())
                <div class="m-6 rounded-xl border-l-4 border-red-500 bg-red-50 p-4">
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

            <form action="{{ route('pendaftaran.update', $pendaftar->id) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="page" value="{{ old('page', $pendaftaranQuery['page'] ?? '') }}">
                <input type="hidden" name="search" value="{{ old('search', $pendaftaranQuery['search'] ?? '') }}">
                <input type="hidden" name="tahun" value="{{ old('tahun', $pendaftaranQuery['tahun'] ?? '') }}">

                <!-- Info Tahun Ajaran -->
                <div class="mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-5 border border-blue-100">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar-alt text-blue-500 mr-2"></i>
                        Tahun Ajaran <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <i class="fas fa-calendar absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <select name="tahun_ajaran_id"
                            class="w-full md:w-1/2 border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all appearance-none bg-white cursor-pointer"
                            required>
                            @foreach ($tahunAjaran as $ta)
                                <option value="{{ $ta->id }}"
                                    {{ old('tahun_ajaran_id', $pendaftar->tahun_ajaran_id) == $ta->id ? 'selected' : '' }}>
                                    {{ $ta->tahun_ajaran }} {{ $ta->status == 'aktif' ? '(Aktif)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        {{-- <i
                            class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i> --}}
                    </div>
                </div>

                <!-- Data Pribadi Calon Siswa -->
                <div class="mb-8">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div
                            class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-3">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">A. Data Pribadi Calon Siswa</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fas fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="text" name="nama_lengkap"
                                    value="{{ old('nama_lengkap', $pendaftar->nama_lengkap) }}"
                                    class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all @error('nama_lengkap') border-red-500 @enderror"
                                    required placeholder="Masukkan nama lengkap">
                            </div>
                            @error('nama_lengkap')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                NISN <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i
                                    class="fas fa-id-card absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="text" name="nisn" value="{{ old('nisn', $pendaftar->nisn) }}"
                                    class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all @error('nisn') border-red-500 @enderror"
                                    required maxlength="10" minlength="10" pattern="[0-9]{10}" placeholder="10 digit angka">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Nomor Induk Siswa Nasional (10 digit)</p>
                            @error('nisn')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                NIK <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i
                                    class="fas fa-address-card absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="text" name="nik" value="{{ old('nik', $pendaftar->nik) }}"
                                    class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all @error('nik') border-red-500 @enderror"
                                    required maxlength="16" minlength="16" pattern="[0-9]{16}" placeholder="16 digit angka">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Nomor Induk Kependudukan (16 digit)</p>
                            @error('nik')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Tempat Lahir <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i
                                    class="fas fa-map-marker-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="text" name="tempat_lahir"
                                    value="{{ old('tempat_lahir', $pendaftar->tempat_lahir) }}"
                                    class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all"
                                    required placeholder="Contoh: Jakarta">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Tanggal Lahir <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i
                                    class="fas fa-calendar-alt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="date" name="tanggal_lahir"
                                    value="{{ old('tanggal_lahir', $pendaftar->tanggal_lahir) }}"
                                    class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all"
                                    required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Jenis Kelamin <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i
                                    class="fas fa-venus-mars absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <select name="jenis_kelamin"
                                    class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all appearance-none bg-white cursor-pointer"
                                    required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L"
                                        {{ old('jenis_kelamin', $pendaftar->jenis_kelamin) == 'L' ? 'selected' : '' }}>
                                        Laki-laki</option>
                                    <option value="P"
                                        {{ old('jenis_kelamin', $pendaftar->jenis_kelamin) == 'P' ? 'selected' : '' }}>
                                        Perempuan</option>
                                </select>
                                {{-- <i
                                    class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i> --}}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Agama <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i
                                    class="fas fa-pray absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <select name="agama"
                                    class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all appearance-none bg-white cursor-pointer"
                                    required>
                                    <option value="">Pilih Agama</option>
                                    <option value="Islam"
                                        {{ old('agama', $pendaftar->agama) == 'Islam' ? 'selected' : '' }}>Islam</option>
                                    <option value="Kristen Protestan"
                                        {{ old('agama', $pendaftar->agama) == 'Kristen Protestan' ? 'selected' : '' }}>
                                        Kristen Protestan</option>
                                    <option value="Katholik"
                                        {{ old('agama', $pendaftar->agama) == 'Katholik' ? 'selected' : '' }}>Katholik
                                    </option>
                                    <option value="Hindu"
                                        {{ old('agama', $pendaftar->agama) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                    <option value="Buddha"
                                        {{ old('agama', $pendaftar->agama) == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                    <option value="Konghucu"
                                        {{ old('agama', $pendaftar->agama) == 'Konghucu' ? 'selected' : '' }}>Konghucu
                                    </option>
                                    <option value="Lainnya"
                                        {{ old('agama', $pendaftar->agama) == 'Lainnya' ? 'selected' : '' }}>Lainnya
                                    </option>
                                </select>
                                {{-- <i
                                    class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i> --}}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alamat Lengkap -->
                <div class="mb-8">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div
                            class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center mr-3">
                            <i class="fas fa-home text-white"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">B. Alamat Lengkap</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Alamat (Jalan/Dusun) <span class="text-red-500">*</span>
                            </label>
                            <textarea name="alamat" rows="3"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all"
                                required placeholder="Masukkan alamat lengkap">{{ old('alamat', $pendaftar->alamat) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">RT</label>
                            <input type="number" name="rt" value="{{ old('rt', $pendaftar->rt) }}"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all"
                                placeholder="Contoh: 001">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">RW</label>
                            <input type="number" name="rw" value="{{ old('rw', $pendaftar->rw) }}"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all"
                                placeholder="Contoh: 002">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Desa/Kelurahan</label>
                            <input type="text" name="desa" value="{{ old('desa', $pendaftar->desa) }}"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all"
                                placeholder="Nama desa/kelurahan">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Kecamatan</label>
                            <input type="text" name="kecamatan" value="{{ old('kecamatan', $pendaftar->kecamatan) }}"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all"
                                placeholder="Nama kecamatan">
                        </div>
                    </div>
                </div>

                <!-- Data Sekolah Asal -->
                <div class="mb-8">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div
                            class="w-10 h-10 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mr-3">
                            <i class="fas fa-school text-white"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">C. Data Asal Sekolah</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Sekolah Asal <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i
                                    class="fas fa-building absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="text" name="sekolah_asal"
                                    value="{{ old('sekolah_asal', $pendaftar->sekolah_asal) }}"
                                    class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all"
                                    required placeholder="Nama SD/MI asal">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Tahun Lulus <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i
                                    class="fas fa-calendar-check absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <select name="tahun_lulus"
                                    class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all appearance-none bg-white cursor-pointer"
                                    required>
                                    <option value="">Pilih Tahun Lulus</option>
                                    @for ($tahun = date('Y'); $tahun >= date('Y') - 5; $tahun--)
                                        <option value="{{ $tahun }}"
                                            {{ old('tahun_lulus', $pendaftar->tahun_lulus) == $tahun ? 'selected' : '' }}>
                                            {{ $tahun }}
                                        </option>
                                    @endfor
                                </select>
                                {{-- <i
                                    class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i> --}}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Kesehatan & Lainnya -->
                <div class="mb-8">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div
                            class="w-10 h-10 bg-gradient-to-r from-pink-500 to-pink-600 rounded-xl flex items-center justify-center mr-3">
                            <i class="fas fa-heartbeat text-white"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">D. Data Kesehatan dan Lainnya</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tinggi Badan (cm)</label>
                            <div class="relative">
                                <i
                                    class="fas fa-ruler absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="number" name="tinggi_badan"
                                    value="{{ old('tinggi_badan', $pendaftar->tinggi_badan) }}"
                                    class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all"
                                    placeholder="cm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Berat Badan (kg)</label>
                            <div class="relative">
                                <i
                                    class="fas fa-weight-scale absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="number" name="berat_badan"
                                    value="{{ old('berat_badan', $pendaftar->berat_badan) }}"
                                    class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all"
                                    placeholder="kg">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Anak Ke-</label>
                            <div class="relative">
                                <i
                                    class="fas fa-child absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="number" name="anak_ke" value="{{ old('anak_ke', $pendaftar->anak_ke) }}"
                                    class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all"
                                    placeholder="Dari berapa bersaudara">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Ukuran Baju</label>
                            <div class="relative">
                                <i
                                    class="fas fa-shirt absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <select name="ukuran_baju"
                                    class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all appearance-none bg-white cursor-pointer">
                                    <option value="">Pilih Ukuran</option>
                                    <option value="S"
                                        {{ old('ukuran_baju', $pendaftar->ukuran_baju) == 'S' ? 'selected' : '' }}>S
                                        (Small)</option>
                                    <option value="M"
                                        {{ old('ukuran_baju', $pendaftar->ukuran_baju) == 'M' ? 'selected' : '' }}>M
                                        (Medium)</option>
                                    <option value="L"
                                        {{ old('ukuran_baju', $pendaftar->ukuran_baju) == 'L' ? 'selected' : '' }}>L
                                        (Large)</option>
                                    <option value="XL"
                                        {{ old('ukuran_baju', $pendaftar->ukuran_baju) == 'XL' ? 'selected' : '' }}>XL
                                        (Extra Large)</option>
                                    <option value="XXL"
                                        {{ old('ukuran_baju', $pendaftar->ukuran_baju) == 'XXL' ? 'selected' : '' }}>XXL
                                        (Double Extra Large)</option>
                                </select>
                                {{-- <i
                                    class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i> --}}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Program Bantuan -->
                <div class="mb-8">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div
                            class="w-10 h-10 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center mr-3">
                            <i class="fas fa-hand-holding-heart text-white"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">E. Program Bantuan (Jika Ada)</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">PKH</label>
                            <input type="text" name="pkh" value="{{ old('pkh', $pendaftar->pkh) }}"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all"
                                placeholder="Nomor PKH">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">KKS</label>
                            <input type="text" name="kks" value="{{ old('kks', $pendaftar->kks) }}"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all"
                                placeholder="Nomor KKS">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">PIP</label>
                            <input type="text" name="pip" value="{{ old('pip', $pendaftar->pip) }}"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all"
                                placeholder="Nomor PIP">
                        </div>
                    </div>
                </div>

                <!-- Data Ayah -->
                <div class="mb-8">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div
                            class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-3">
                            <i class="fas fa-male text-white"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">F. Data Ayah</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Ayah <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_ayah" value="{{ old('nama_ayah', $pendaftar->nama_ayah) }}"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all"
                                required placeholder="Nama lengkap ayah">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun Lahir Ayah</label>
                            <input type="text" name="tahun_lahir_ayah"
                                value="{{ old('tahun_lahir_ayah', $pendaftar->tahun_lahir_ayah) }}"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all"
                                placeholder="Contoh: 1975" maxlength="4">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Pekerjaan Ayah</label>
                            <div class="relative">
                                <i
                                    class="fas fa-briefcase absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <select name="pekerjaan_ayah"
                                    class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all appearance-none bg-white cursor-pointer">
                                    <option value="">Pilih Pekerjaan</option>
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
                                            {{ $p }}
                                        </option>
                                    @endforeach
                                </select>
                                {{-- <i
                                    class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i> --}}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Pendidikan Ayah</label>
                            <div class="relative">
                                <i
                                    class="fas fa-graduation-cap absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <select name="pendidikan_ayah"
                                    class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all appearance-none bg-white cursor-pointer">
                                    <option value="">Pilih Pendidikan</option>
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
                                            {{ $p }}
                                        </option>
                                    @endforeach
                                </select>
                                {{-- <i
                                    class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i> --}}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Ibu -->
                <div class="mb-8">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div
                            class="w-10 h-10 bg-gradient-to-r from-pink-500 to-pink-600 rounded-xl flex items-center justify-center mr-3">
                            <i class="fas fa-female text-white"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">G. Data Ibu</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Ibu <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_ibu" value="{{ old('nama_ibu', $pendaftar->nama_ibu) }}"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all"
                                required placeholder="Nama lengkap ibu">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun Lahir Ibu</label>
                            <input type="text" name="tahun_lahir_ibu"
                                value="{{ old('tahun_lahir_ibu', $pendaftar->tahun_lahir_ibu) }}"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all"
                                placeholder="Contoh: 1978" maxlength="4">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Pekerjaan Ibu</label>
                            <div class="relative">
                                <i
                                    class="fas fa-briefcase absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <select name="pekerjaan_ibu"
                                    class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all appearance-none bg-white cursor-pointer">
                                    <option value="">Pilih Pekerjaan</option>
                                    @foreach ($pekerjaan as $p)
                                        <option value="{{ $p }}"
                                            {{ old('pekerjaan_ibu', $pendaftar->pekerjaan_ibu) == $p ? 'selected' : '' }}>
                                            {{ $p }}
                                        </option>
                                    @endforeach
                                </select>
                                {{-- <i
                                    class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i> --}}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Pendidikan Ibu</label>
                            <div class="relative">
                                <i
                                    class="fas fa-graduation-cap absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <select name="pendidikan_ibu"
                                    class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all appearance-none bg-white cursor-pointer">
                                    <option value="">Pilih Pendidikan</option>
                                    @foreach ($pendidikan as $p)
                                        <option value="{{ $p }}"
                                            {{ old('pendidikan_ibu', $pendaftar->pendidikan_ibu) == $p ? 'selected' : '' }}>
                                            {{ $p }}
                                        </option>
                                    @endforeach
                                </select>
                                {{-- <i
                                    class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i> --}}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Wali -->
                <div class="mb-8">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div
                            class="w-10 h-10 bg-gradient-to-r from-teal-500 to-teal-600 rounded-xl flex items-center justify-center mr-3">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">H. Data Wali (Jika Bukan Orang Tua Kandung)</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Wali</label>
                            <input type="text" name="nama_wali" value="{{ old('nama_wali', $pendaftar->nama_wali) }}"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all"
                                placeholder="Nama lengkap wali">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun Lahir Wali</label>
                            <input type="text" name="tahun_lahir_wali"
                                value="{{ old('tahun_lahir_wali', $pendaftar->tahun_lahir_wali) }}"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all"
                                placeholder="Contoh: 1975" maxlength="4">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Pekerjaan Wali</label>
                            <div class="relative">
                                <i
                                    class="fas fa-briefcase absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <select name="pekerjaan_wali"
                                    class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all appearance-none bg-white cursor-pointer">
                                    <option value="">Pilih Pekerjaan</option>
                                    @foreach ($pekerjaan as $p)
                                        <option value="{{ $p }}"
                                            {{ old('pekerjaan_wali', $pendaftar->pekerjaan_wali) == $p ? 'selected' : '' }}>
                                            {{ $p }}
                                        </option>
                                    @endforeach
                                </select>
                                {{-- <i
                                    class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i> --}}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Pendidikan Wali</label>
                            <div class="relative">
                                <i
                                    class="fas fa-graduation-cap absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <select name="pendidikan_wali"
                                    class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all appearance-none bg-white cursor-pointer">
                                    <option value="">Pilih Pendidikan</option>
                                    @foreach ($pendidikan as $p)
                                        <option value="{{ $p }}"
                                            {{ old('pendidikan_wali', $pendaftar->pendidikan_wali) == $p ? 'selected' : '' }}>
                                            {{ $p }}
                                        </option>
                                    @endforeach
                                </select>
                                {{-- <i
                                    class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i> --}}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nomor Kontak -->
                <div class="mb-8">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div
                            class="w-10 h-10 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center mr-3">
                            <i class="fas fa-phone-alt text-white"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">I. Nomor Kontak (Wajib)</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                No. HP Siswa
                            </label>
                            <div class="relative">
                                <i
                                    class="fab fa-whatsapp absolute left-3 top-1/2 transform -translate-y-1/2 text-green-500"></i>
                                <input type="tel" name="no_hp_siswa"
                                    value="{{ old('no_hp_siswa', $pendaftar->no_hp_siswa) }}"
                                    class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all"
                                    minlength="9" maxlength="16" placeholder="Contoh: 081234567890">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Nomor WhatsApp/Seluler siswa yang bisa dihubungi</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                No. HP Orang Tua/Wali <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i
                                    class="fab fa-whatsapp absolute left-3 top-1/2 transform -translate-y-1/2 text-green-500"></i>
                                <input type="tel" name="no_hp_ortu"
                                    value="{{ old('no_hp_ortu', $pendaftar->no_hp_ortu) }}"
                                    class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all"
                                    required minlength="9" maxlength="16" placeholder="Contoh: 081234567890">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Nomor WhatsApp/Telepon orang tua/wali yang bisa dihubungi
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 pt-4 border-t border-gray-200 flex justify-end space-x-4">
                    <a href="{{ route('pendaftaran.show', $pendaftar->id) }}"
                        class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all font-medium">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-yellow-500 to-orange-500 text-white rounded-xl hover:shadow-lg transition-all font-medium">
                        <i class="fas fa-save mr-2"></i>
                        Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
