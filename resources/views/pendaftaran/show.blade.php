@extends('layouts.app')

@section('title', 'Detail Pendaftar')

@section('content')
    <div class="div mt-8">
        <!-- Header Section with Gradient -->
        <div
            class="mb-8 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden">
            {{-- <div class="absolute top-0 right-0 opacity-10">
                <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L15 8.5L22 9.5L17 14L18.5 21L12 17.5L5.5 21L7 14L2 9.5L9 8.5L12 2Z" />
                </svg>
            </div> --}}
            <div class="relative z-10">
                <div class="flex justify-between items-center flex-wrap gap-4">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Detail Calon Siswa</h1>
                        <div class="flex items-center space-x-3 flex-wrap gap-2">
                            <p class="text-blue-100">Informasi lengkap data pendaftar</p>
                            @if ($pendaftar->trashed())
                                <span class="px-3 py-1 bg-red-500 bg-opacity-80 rounded-full text-sm font-semibold">
                                    <i class="fas fa-trash-alt mr-1"></i>Data telah dihapus
                                    ({{ $pendaftar->deleted_at->format('d/m/Y H:i') }})
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="flex space-x-3 flex-wrap gap-2">
                        @if ($pendaftar->trashed() && auth()->user()->role === 'admin')
                            <form action="{{ route('pendaftaran.restore', $pendaftar->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    class="bg-green-500 hover:bg-green-600 text-white px-5 py-2.5 rounded-xl transition-all font-medium shadow-md hover:shadow-lg">
                                    <i class="fas fa-undo mr-2"></i>Restore Data
                                </button>
                            </form>
                        @endif

                        @if (auth()->user()->role === 'admin' && !$pendaftar->trashed())
                            <a href="{{ route('pendaftaran.edit', $pendaftar->id) }}"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2.5 rounded-xl transition-all font-medium shadow-md hover:shadow-lg">
                                <i class="fas fa-edit mr-2"></i>Edit Data
                            </a>
                        @endif

                        <a href="{{ route('pendaftaran.cetak', $pendaftar->id) }}" target="_blank"
                            class="bg-green-500 hover:bg-green-600 text-white px-5 py-2.5 rounded-xl transition-all font-medium shadow-md hover:shadow-lg">
                            <i class="fas fa-print mr-2"></i>Cetak Kartu
                        </a>
                        <a href="{{ route('pendaftaran.index') }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2.5 rounded-xl transition-all font-medium shadow-md hover:shadow-lg">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="p-6">
                <!-- Header Info Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pb-6 mb-6 border-b border-gray-200">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4">
                        <p class="text-sm text-gray-500 mb-1">
                            <i class="fas fa-id-card text-blue-500 mr-1"></i> Nomor Peserta
                        </p>
                        <p class="text-2xl font-bold text-blue-600 font-mono">{{ $pendaftar->no_peserta }}</p>
                    </div>
                    <div class="bg-gradient-to-r from-green-50 to-teal-50 rounded-xl p-4">
                        <p class="text-sm text-gray-500 mb-1">
                            <i class="fas fa-calendar-alt text-green-500 mr-1"></i> Tahun Ajaran
                        </p>
                        <p class="text-xl font-bold text-gray-800">{{ $pendaftar->tahunAjaran->tahun_ajaran ?? '-' }}</p>
                    </div>
                </div>

                <!-- Request Satu Kelaskan Info -->
                @if ($pendaftar->hasGroupingRequest())
                    <div class="mb-6 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl border border-indigo-200 p-5">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-users text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="text-lg font-bold text-indigo-800 mb-2">
                                    Informasi Satu Kelaskan
                                </h3>

                                @if ($pendaftar->groupingRequest)
                                    <div class="space-y-2">
                                        <p class="text-sm text-gray-700">
                                            <strong class="text-indigo-700">Group:</strong>
                                            <span class="font-semibold">{{ $pendaftar->groupingRequest->group_name }}</span>
                                        </p>
                                        <p class="text-sm text-gray-700">
                                            <strong class="text-indigo-700">Status:</strong>
                                            {!! $pendaftar->groupingRequest->status_badge !!}
                                        </p>
                                        <div>
                                            <p class="text-sm text-gray-700 mb-2">
                                                <strong class="text-indigo-700">Anggota Group:</strong>
                                            </p>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach ($pendaftar->groupingRequest->students as $student)
                                                    <span class="px-3 py-1 bg-white rounded-lg text-sm shadow-sm">
                                                        {{ $student->nama_lengkap }}
                                                        <span
                                                            class="text-xs text-gray-500">({{ $student->no_peserta }})</span>
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @elseif($pendaftar->requested_with_names)
                                    <div class="space-y-2">
                                        <p class="text-sm text-gray-700">
                                            <strong class="text-indigo-700">Meminta satu kelas dengan:</strong><br>
                                            <span class="font-semibold">{{ $pendaftar->formatted_requested_names }}</span>
                                        </p>
                                        <p class="text-sm">
                                            <span
                                                class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-lg inline-flex items-center">
                                                <i class="fas fa-clock mr-1"></i> Menunggu verifikasi admin
                                            </span>
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- A. Data Pribadi -->
                <div class="mb-6">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div
                            class="w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-800">A. Data Pribadi Calon Siswa</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">Nama Lengkap</p>
                            <p class="font-semibold text-gray-800">{{ $pendaftar->nama_lengkap }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">NISN</p>
                            <p class="font-semibold font-mono text-gray-800">{{ $pendaftar->nisn }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">NIK</p>
                            <p class="font-semibold font-mono text-gray-800">{{ $pendaftar->nik ?? '-' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">Tempat, Tanggal Lahir</p>
                            <p class="font-semibold text-gray-800">{{ $pendaftar->tempat_lahir }},
                                {{ \Carbon\Carbon::parse($pendaftar->tanggal_lahir)->format('d/m/Y') }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">Jenis Kelamin</p>
                            <p class="font-semibold text-gray-800">
                                @if ($pendaftar->jenis_kelamin == 'L')
                                    <span class="text-blue-600"><i class="fas fa-mars mr-1"></i> Laki-laki</span>
                                @else
                                    <span class="text-pink-600"><i class="fas fa-venus mr-1"></i> Perempuan</span>
                                @endif
                            </p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">Agama</p>
                            <p class="font-semibold text-gray-800">{{ $pendaftar->agama }}</p>
                        </div>
                    </div>
                </div>

                <!-- B. Alamat -->
                <div class="mb-6">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div
                            class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-home text-white text-sm"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-800">B. Alamat Lengkap</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2 bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">Alamat</p>
                            <p class="font-semibold text-gray-800">{{ $pendaftar->alamat }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">RT/RW</p>
                            <p class="font-semibold text-gray-800">RT. {{ $pendaftar->rt ?? '-' }} / RW.
                                {{ $pendaftar->rw ?? '-' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">Desa/Kelurahan</p>
                            <p class="font-semibold text-gray-800">{{ $pendaftar->desa ?? '-' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">Kecamatan</p>
                            <p class="font-semibold text-gray-800">{{ $pendaftar->kecamatan ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- C. Sekolah Asal -->
                <div class="mb-6">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div
                            class="w-8 h-8 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-school text-white text-sm"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-800">C. Data Asal Sekolah</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">Sekolah Asal</p>
                            <p class="font-semibold text-gray-800">{{ $pendaftar->sekolah_asal }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">Tahun Lulus</p>
                            <p class="font-semibold text-gray-800">{{ $pendaftar->tahun_lulus }}</p>
                        </div>
                    </div>
                </div>

                <!-- D. Kesehatan -->
                <div class="mb-6">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div
                            class="w-8 h-8 bg-gradient-to-r from-pink-500 to-pink-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-heartbeat text-white text-sm"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-800">D. Data Kesehatan & Lainnya</h2>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">Tinggi Badan</p>
                            <p class="font-semibold text-gray-800">{{ $pendaftar->tinggi_badan ?? '-' }} cm</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">Berat Badan</p>
                            <p class="font-semibold text-gray-800">{{ $pendaftar->berat_badan ?? '-' }} kg</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">Anak Ke-</p>
                            <p class="font-semibold text-gray-800">{{ $pendaftar->anak_ke ?? '-' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">Ukuran Baju</p>
                            <p class="font-semibold text-gray-800">{{ $pendaftar->ukuran_baju ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- E. Program Bantuan -->
                <div class="mb-6">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div
                            class="w-8 h-8 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-hand-holding-heart text-white text-sm"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-800">E. Program Bantuan</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">PKH</p>
                            <p class="font-semibold font-mono text-gray-800">{{ $pendaftar->pkh ?? '-' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">KKS</p>
                            <p class="font-semibold font-mono text-gray-800">{{ $pendaftar->kks ?? '-' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">PIP</p>
                            <p class="font-semibold font-mono text-gray-800">{{ $pendaftar->pip ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- F. Data Ayah -->
                <div class="mb-6">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div
                            class="w-8 h-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-male text-white text-sm"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-800">F. Data Ayah</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">Nama Ayah</p>
                            <p class="font-semibold text-gray-800">{{ $pendaftar->nama_ayah }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">Tahun Lahir</p>
                            <p class="font-semibold text-gray-800">{{ $pendaftar->tahun_lahir_ayah ?? '-' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">Pekerjaan</p>
                            <p class="font-semibold text-gray-800">{{ $pendaftar->pekerjaan_ayah ?? '-' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">Pendidikan</p>
                            <p class="font-semibold text-gray-800">{{ $pendaftar->pendidikan_ayah ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- G. Data Ibu -->
                <div class="mb-6">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div
                            class="w-8 h-8 bg-gradient-to-r from-pink-500 to-pink-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-female text-white text-sm"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-800">G. Data Ibu</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">Nama Ibu</p>
                            <p class="font-semibold text-gray-800">{{ $pendaftar->nama_ibu }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">Tahun Lahir</p>
                            <p class="font-semibold text-gray-800">{{ $pendaftar->tahun_lahir_ibu ?? '-' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">Pekerjaan</p>
                            <p class="font-semibold text-gray-800">{{ $pendaftar->pekerjaan_ibu ?? '-' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">Pendidikan</p>
                            <p class="font-semibold text-gray-800">{{ $pendaftar->pendidikan_ibu ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- H. Data Wali -->
                @if ($pendaftar->nama_wali)
                    <div class="mb-6">
                        <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                            <div
                                class="w-8 h-8 bg-gradient-to-r from-teal-500 to-teal-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-users text-white text-sm"></i>
                            </div>
                            <h2 class="text-lg font-bold text-gray-800">H. Data Wali</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-xs text-gray-500">Nama Wali</p>
                                <p class="font-semibold text-gray-800">{{ $pendaftar->nama_wali }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-xs text-gray-500">Tahun Lahir</p>
                                <p class="font-semibold text-gray-800">{{ $pendaftar->tahun_lahir_wali ?? '-' }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-xs text-gray-500">Pekerjaan</p>
                                <p class="font-semibold text-gray-800">{{ $pendaftar->pekerjaan_wali ?? '-' }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-xs text-gray-500">Pendidikan</p>
                                <p class="font-semibold text-gray-800">{{ $pendaftar->pendidikan_wali ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- I. Nomor Kontak -->
                <div class="mb-6">
                    <div class="flex items-center mb-4 pb-2 border-b border-gray-200">
                        <div
                            class="w-8 h-8 bg-gradient-to-r from-green-500 to-green-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-phone-alt text-white text-sm"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-800">I. Nomor Kontak</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">No. HP Siswa</p>
                            <p class="font-semibold font-mono text-gray-800">{{ $pendaftar->no_hp_siswa ?? '-' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-xs text-gray-500">No. HP Orang Tua/Wali</p>
                            <p class="font-semibold font-mono text-gray-800">{{ $pendaftar->no_hp_ortu }}</p>
                        </div>
                    </div>
                </div>

                <!-- Footer Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-6 mt-4 border-t border-gray-200">
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fas fa-clock text-gray-400 mr-2"></i>
                        <span>Tanggal Daftar: {{ $pendaftar->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-500 md:text-right">
                        <i class="fas fa-edit text-gray-400 mr-2"></i>
                        <span>Terakhir Update: {{ $pendaftar->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
