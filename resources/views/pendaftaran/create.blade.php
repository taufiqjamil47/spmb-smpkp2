@extends('layouts.app')

@section('title', 'Pendaftaran Baru')

@section('content')
    <div class="div mt-8">
        <!-- Header Section with Gradient -->
        <div
            class="mb-8 bg-gradient-to-r from-green-600 to-teal-600 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden">
            {{-- <div class="absolute top-0 right-0 opacity-10">
                <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L15 8.5L22 9.5L17 14L18.5 21L12 17.5L5.5 21L7 14L2 9.5L9 8.5L12 2Z" />
                </svg>
            </div> --}}
            <div class="relative z-10">
                <h1 class="text-3xl font-bold mb-2">Form Pendaftaran Siswa Baru</h1>
                <div class="flex items-center space-x-4 flex-wrap gap-2">
                    <p class="text-green-100">Tahun Ajaran {{ $tahunAjaranAktif->tahun_ajaran }}</p>
                    @php
                        $sisaKuota =
                            $tahunAjaranAktif->kuota -
                            \App\Models\CalonSiswa::where('tahun_ajaran_id', $tahunAjaranAktif->id)->count();
                        $persentaseKuota = round(($sisaKuota / $tahunAjaranAktif->kuota) * 100);
                    @endphp
                    <span class="px-3 py-1 bg-white bg-opacity-20 rounded-full text-sm">
                        <i class="fas fa-ticket-alt mr-1"></i>
                        Sisa Kuota: {{ $sisaKuota }} / {{ $tahunAjaranAktif->kuota }}
                    </span>
                    @if ($sisaKuota < 10)
                        <span class="px-3 py-1 bg-red-500 bg-opacity-80 rounded-full text-sm animate-pulse">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Kuota Terbatas!
                        </span>
                    @endif
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

            <form action="{{ route('pendaftaran.store') }}" method="POST" class="p-6">
                @csrf

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
                                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}"
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
                                <input type="text" name="nisn" value="{{ old('nisn') }}"
                                    class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all @error('nisn') border-red-500 @enderror"
                                    required maxlength="10" minlength="10" pattern="[0-9]{10}" inputmode="numeric"
                                    placeholder="10 digit angka">
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
                                <input type="text" name="nik" value="{{ old('nik') }}"
                                    class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all @error('nik') border-red-500 @enderror"
                                    required maxlength="16" minlength="16" pattern="[0-9]{16}" inputmode="numeric"
                                    placeholder="16 digit angka">
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
                                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}"
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
                                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
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
                                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki
                                    </option>
                                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan
                                    </option>
                                </select>
                                <i
                                    class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Agama <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fas fa-pray absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <select name="agama"
                                    class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all appearance-none bg-white cursor-pointer"
                                    required>
                                    <option value="">Pilih Agama</option>
                                    <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                    <option value="Kristen Protestan"
                                        {{ old('agama') == 'Kristen Protestan' ? 'selected' : '' }}>Kristen Protestan
                                    </option>
                                    <option value="Katholik" {{ old('agama') == 'Katholik' ? 'selected' : '' }}>Katholik
                                    </option>
                                    <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                    <option value="Buddha" {{ old('agama') == 'Buddha' ? 'selected' : '' }}>Buddha
                                    </option>
                                    <option value="Konghucu" {{ old('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu
                                    </option>
                                    <option value="Lainnya" {{ old('agama') == 'Lainnya' ? 'selected' : '' }}>Lainnya
                                    </option>
                                </select>
                                <i
                                    class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
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
                                required placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">RT</label>
                            <input type="number" name="rt" value="{{ old('rt') }}"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all"
                                placeholder="Contoh: 001">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">RW</label>
                            <input type="number" name="rw" value="{{ old('rw') }}"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all"
                                placeholder="Contoh: 002">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Desa/Kelurahan</label>
                            <input type="text" name="desa" value="{{ old('desa') }}"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all"
                                placeholder="Nama desa/kelurahan">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Kecamatan</label>
                            <input type="text" name="kecamatan" value="{{ old('kecamatan') }}"
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
                                <input type="text" name="sekolah_asal" value="{{ old('sekolah_asal') }}"
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
                                            {{ old('tahun_lulus') == $tahun ? 'selected' : '' }}>
                                            {{ $tahun }}
                                        </option>
                                    @endfor
                                </select>
                                <i
                                    class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
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
                                <input type="number" name="tinggi_badan" value="{{ old('tinggi_badan') }}"
                                    class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all"
                                    placeholder="cm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Berat Badan (kg)</label>
                            <div class="relative">
                                <i
                                    class="fas fa-weight-scale absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="number" name="berat_badan" value="{{ old('berat_badan') }}"
                                    class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all"
                                    placeholder="kg">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Anak Ke-</label>
                            <div class="relative">
                                <i
                                    class="fas fa-child absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="number" name="anak_ke" value="{{ old('anak_ke') }}"
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
                                    <option value="S" {{ old('ukuran_baju') == 'S' ? 'selected' : '' }}>S (Small)
                                    </option>
                                    <option value="M" {{ old('ukuran_baju') == 'M' ? 'selected' : '' }}>M (Medium)
                                    </option>
                                    <option value="L" {{ old('ukuran_baju') == 'L' ? 'selected' : '' }}>L (Large)
                                    </option>
                                    <option value="XL" {{ old('ukuran_baju') == 'XL' ? 'selected' : '' }}>XL (Extra
                                        Large)</option>
                                    <option value="XXL" {{ old('ukuran_baju') == 'XXL' ? 'selected' : '' }}>XXL (Double
                                        Extra Large)</option>
                                </select>
                                <i
                                    class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
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
                            <input type="text" name="pkh" value="{{ old('pkh') }}"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all"
                                placeholder="Nomor PKH">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">KKS</label>
                            <input type="text" name="kks" value="{{ old('kks') }}"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all"
                                placeholder="Nomor KKS">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">PIP</label>
                            <input type="text" name="pip" value="{{ old('pip') }}"
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
                            <input type="text" name="nama_ayah" value="{{ old('nama_ayah') }}"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all"
                                required placeholder="Nama lengkap ayah">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun Lahir Ayah</label>
                            <input type="text" name="tahun_lahir_ayah" value="{{ old('tahun_lahir_ayah') }}"
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
                                            {{ old('pekerjaan_ayah') == $p ? 'selected' : '' }}>
                                            {{ $p }}
                                        </option>
                                    @endforeach
                                </select>
                                <i
                                    class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
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
                                            {{ old('pendidikan_ayah') == $p ? 'selected' : '' }}>
                                            {{ $p }}
                                        </option>
                                    @endforeach
                                </select>
                                <i
                                    class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
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
                            <input type="text" name="nama_ibu" value="{{ old('nama_ibu') }}"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all"
                                required placeholder="Nama lengkap ibu">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun Lahir Ibu</label>
                            <input type="text" name="tahun_lahir_ibu" value="{{ old('tahun_lahir_ibu') }}"
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
                                            {{ old('pekerjaan_ibu') == $p ? 'selected' : '' }}>
                                            {{ $p }}
                                        </option>
                                    @endforeach
                                </select>
                                <i
                                    class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
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
                                            {{ old('pendidikan_ibu') == $p ? 'selected' : '' }}>
                                            {{ $p }}
                                        </option>
                                    @endforeach
                                </select>
                                <i
                                    class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Wali (Opsional) -->
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
                            <input type="text" name="nama_wali" value="{{ old('nama_wali') }}"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all"
                                placeholder="Nama lengkap wali">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun Lahir Wali</label>
                            <input type="text" name="tahun_lahir_wali" value="{{ old('tahun_lahir_wali') }}"
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
                                            {{ old('pekerjaan_wali') == $p ? 'selected' : '' }}>
                                            {{ $p }}
                                        </option>
                                    @endforeach
                                </select>
                                <i
                                    class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
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
                                            {{ old('pendidikan_wali') == $p ? 'selected' : '' }}>
                                            {{ $p }}
                                        </option>
                                    @endforeach
                                </select>
                                <i
                                    class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- No untuk dihubungi -->
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
                                No. HP Siswa <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i
                                    class="fab fa-whatsapp absolute left-3 top-1/2 transform -translate-y-1/2 text-green-500"></i>
                                <input type="tel" name="no_hp_siswa" value="{{ old('no_hp_siswa') }}"
                                    class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all @error('no_hp_siswa') border-red-500 @enderror"
                                    required minlength="9" maxlength="16" pattern="[0-9+ ]{9,16}" inputmode="tel"
                                    placeholder="Contoh: 081234567890">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Nomor WhatsApp/Seluler siswa yang bisa dihubungi</p>
                            @error('no_hp_siswa')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                No. HP Orang Tua/Wali <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i
                                    class="fab fa-whatsapp absolute left-3 top-1/2 transform -translate-y-1/2 text-green-500"></i>
                                <input type="tel" name="no_hp_ortu" value="{{ old('no_hp_ortu') }}"
                                    class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all @error('no_hp_ortu') border-red-500 @enderror"
                                    required minlength="9" maxlength="16" pattern="[0-9+ ]{9,16}" inputmode="tel"
                                    placeholder="Contoh: 081234567890">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Nomor WhatsApp/Telepon orang tua/wali yang bisa dihubungi
                            </p>
                            @error('no_hp_ortu')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Request Satu Kelaskan -->
                <div class="mb-8">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div
                            class="w-10 h-10 bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl flex items-center justify-center mr-3">
                            <i class="fas fa-handshake text-white"></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">J. Request Satu Kelaskan (Opsional)</h2>
                    </div>

                    <div
                        class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl border-l-4 border-yellow-500 p-4 mb-5">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-yellow-600 text-lg"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-800">
                                    <strong>Informasi Penting:</strong> Jika ingin ditempatkan satu kelas dengan teman
                                    tertentu,
                                    silakan isi form di bawah ini.
                                    <br>Mohon diisi dengan <strong>nama lengkap sesuai data pendaftaran</strong> teman yang
                                    dimaksud.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Request satu kelas dengan (pisahkan dengan koma)
                            </label>
                            <div class="relative">
                                <i class="fas fa-user-friends absolute left-3 top-3 text-gray-400"></i>
                                <input type="text" name="requested_with_names_raw"
                                    value="{{ old('requested_with_names_raw') }}"
                                    class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all"
                                    placeholder="Contoh: Ahmad Fauzi, Siti Nurhaliza, Budi Santoso">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-info-circle"></i>
                                Masukkan nama lengkap teman yang diinginkan, pisahkan dengan koma.
                                Request ini akan diproses oleh admin saat pembagian kelas.
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Prioritas Request
                            </label>
                            <div class="relative">
                                <i
                                    class="fas fa-flag absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <select name="grouping_priority"
                                    class="w-full md:w-1/2 border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition-all appearance-none bg-white cursor-pointer">
                                    <option value="none" {{ old('grouping_priority') == 'none' ? 'selected' : '' }}>
                                        Normal</option>
                                    <option value="medium" {{ old('grouping_priority') == 'medium' ? 'selected' : '' }}>
                                        <i class="fas fa-flag"></i> Prioritas Sedang
                                    </option>
                                    <option value="high" {{ old('grouping_priority') == 'high' ? 'selected' : '' }}>
                                        <i class="fas fa-flag-checkered"></i> Prioritas Tinggi
                                    </option>
                                </select>
                                <i
                                    class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Prioritas tinggi akan diusahakan lebih dahulu oleh admin
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 pt-4 border-t border-gray-200 flex justify-end space-x-4">
                    <a href="{{ route('pendaftaran.index') }}"
                        class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all font-medium">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:shadow-lg transition-all font-medium">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Pendaftaran
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
