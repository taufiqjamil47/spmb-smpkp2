@extends('layouts.app')

@section('title', 'Detail Pendaftar')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold">Detail Calon Siswa</h1>
            @if ($pendaftar->trashed())
                <div class="mt-2">
                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold">
                        <i class="fas fa-trash-alt mr-1"></i>Data telah dihapus
                        ({{ $pendaftar->deleted_at->format('d/m/Y H:i') }})
                    </span>
                </div>
            @endif
        </div>
        <div>
            @if ($pendaftar->trashed() && auth()->user()->role === 'admin')
                <form action="{{ route('pendaftaran.restore', $pendaftar->id) }}" method="POST" class="inline mr-2">
                    @csrf
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                        <i class="fas fa-undo mr-2"></i>Restore Data
                    </button>
                </form>
            @endif

            @if (auth()->user()->role === 'admin' && !$pendaftar->trashed())
                <a href="{{ route('pendaftaran.edit', $pendaftar->id) }}"
                    class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 mr-2">
                    <i class="fas fa-edit mr-2"></i>Edit Data
                </a>
            @endif

            <a href="{{ route('pendaftaran.cetak', $pendaftar->id) }}" target="_blank"
                class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 mr-2">
                <i class="fas fa-print mr-2"></i>Cetak Kartu
            </a>
            <a href="{{ route('pendaftaran.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <!-- Header Info -->
        <div class="grid grid-cols-2 gap-6 pb-4 mb-4 border-b">
            <div>
                <p class="text-sm text-gray-600">Nomor Peserta</p>
                <p class="text-xl font-bold text-blue-600">{{ $pendaftar->no_peserta }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Tahun Ajaran</p>
                <p class="font-semibold">{{ $pendaftar->tahunAjaran->tahun_ajaran ?? '-' }}</p>
            </div>
        </div>

        <!-- A. Data Pribadi -->
        <div class="mb-6">
            <h2 class="text-lg font-semibold bg-blue-50 p-2 mb-4">A. Data Pribadi Calon Siswa</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Nama Lengkap</p>
                    <p class="font-semibold">{{ $pendaftar->nama_lengkap }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">NISN</p>
                    <p class="font-semibold">{{ $pendaftar->nisn }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">NIK</p>
                    <p class="font-semibold">{{ $pendaftar->nik ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tempat, Tanggal Lahir</p>
                    <p class="font-semibold">{{ $pendaftar->tempat_lahir }},
                        {{ $pendaftar->tanggal_lahir }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Jenis Kelamin</p>
                    <p class="font-semibold">{{ $pendaftar->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Agama</p>
                    <p class="font-semibold">{{ $pendaftar->agama }}</p>
                </div>
            </div>
        </div>

        <!-- B. Alamat -->
        <div class="mb-6">
            <h2 class="text-lg font-semibold bg-blue-50 p-2 mb-4">B. Alamat Lengkap</h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <p class="text-sm text-gray-600">Alamat</p>
                    <p class="font-semibold">{{ $pendaftar->alamat }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">RT/RW</p>
                    <p class="font-semibold">RT. {{ $pendaftar->rt ?? '-' }} / RW. {{ $pendaftar->rw ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Desa/Kelurahan</p>
                    <p class="font-semibold">{{ $pendaftar->desa ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Kecamatan</p>
                    <p class="font-semibold">{{ $pendaftar->kecamatan ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">No. HP/Telepon</p>
                    <p class="font-semibold">{{ $pendaftar->no_hp_siswa ?? '-' }} / {{ $pendaftar->no_telp ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- C. Sekolah Asal -->
        <div class="mb-6">
            <h2 class="text-lg font-semibold bg-blue-50 p-2 mb-4">C. Data Asal Sekolah</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Sekolah Asal</p>
                    <p class="font-semibold">{{ $pendaftar->sekolah_asal }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tahun Lulus</p>
                    <p class="font-semibold">{{ $pendaftar->tahun_lulus }}</p>
                </div>
            </div>
        </div>

        <!-- D. Kesehatan -->
        <div class="mb-6">
            <h2 class="text-lg font-semibold bg-blue-50 p-2 mb-4">D. Data Kesehatan & Lainnya</h2>
            <div class="grid grid-cols-4 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Tinggi Badan</p>
                    <p class="font-semibold">{{ $pendaftar->tinggi_badan ?? '-' }} cm</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Berat Badan</p>
                    <p class="font-semibold">{{ $pendaftar->berat_badan ?? '-' }} kg</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Anak Ke-</p>
                    <p class="font-semibold">{{ $pendaftar->anak_ke ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Ukuran Baju</p>
                    <p class="font-semibold">{{ $pendaftar->ukuran_baju ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- E. Program Bantuan -->
        <div class="mb-6">
            <h2 class="text-lg font-semibold bg-blue-50 p-2 mb-4">E. Program Bantuan</h2>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <p class="text-sm text-gray-600">PKH</p>
                    <p class="font-semibold">{{ $pendaftar->pkh ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">KKS</p>
                    <p class="font-semibold">{{ $pendaftar->kks ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">PIP</p>
                    <p class="font-semibold">{{ $pendaftar->pip ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- F. Data Ayah -->
        <div class="mb-6">
            <h2 class="text-lg font-semibold bg-blue-50 p-2 mb-4">F. Data Ayah</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Nama Ayah</p>
                    <p class="font-semibold">{{ $pendaftar->nama_ayah }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tahun Lahir</p>
                    <p class="font-semibold">{{ $pendaftar->tahun_lahir_ayah ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Pekerjaan</p>
                    <p class="font-semibold">{{ $pendaftar->pekerjaan_ayah ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Pendidikan</p>
                    <p class="font-semibold">{{ $pendaftar->pendidikan_ayah ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- G. Data Ibu -->
        <div class="mb-6">
            <h2 class="text-lg font-semibold bg-blue-50 p-2 mb-4">G. Data Ibu</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Nama Ibu</p>
                    <p class="font-semibold">{{ $pendaftar->nama_ibu }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tahun Lahir</p>
                    <p class="font-semibold">{{ $pendaftar->tahun_lahir_ibu ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Pekerjaan</p>
                    <p class="font-semibold">{{ $pendaftar->pekerjaan_ibu ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Pendidikan</p>
                    <p class="font-semibold">{{ $pendaftar->pendidikan_ibu ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- H. Data Wali -->
        @if ($pendaftar->nama_wali)
            <div class="mb-6">
                <h2 class="text-lg font-semibold bg-blue-50 p-2 mb-4">H. Data Wali</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Nama Wali</p>
                        <p class="font-semibold">{{ $pendaftar->nama_wali }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Tahun Lahir</p>
                        <p class="font-semibold">{{ $pendaftar->tahun_lahir_wali ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Pekerjaan</p>
                        <p class="font-semibold">{{ $pendaftar->pekerjaan_wali ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Pendidikan</p>
                        <p class="font-semibold">{{ $pendaftar->pendidikan_wali ?? '-' }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Kontak Orang Tua -->
        <div class="mb-6">
            <h2 class="text-lg font-semibold bg-blue-50 p-2 mb-4">Kontak Orang Tua/Wali</h2>
            <div>
                <p class="text-sm text-gray-600">No. HP Orang Tua/Wali</p>
                <p class="font-semibold">{{ $pendaftar->no_hp_ortu }}</p>
            </div>
        </div>

        <!-- Footer Info -->
        <div class="grid grid-cols-2 gap-4 pt-4 mt-4 border-t text-sm text-gray-500">
            <div>
                <p>Tanggal Daftar: {{ $pendaftar->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div>
                <p>Terakhir Update: {{ $pendaftar->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
@endsection
