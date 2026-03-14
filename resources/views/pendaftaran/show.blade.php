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
        <div class="grid grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-600">Nomor Peserta</p>
                <p class="text-xl font-bold text-blue-600">{{ $pendaftar->no_peserta }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Tahun Ajaran</p>
                <p class="font-semibold">{{ $pendaftar->tahunAjaran->tahun_ajaran }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6 mt-6">
            <div>
                <p class="text-sm text-gray-600">Nama Lengkap</p>
                <p class="font-semibold">{{ $pendaftar->nama_lengkap }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">NISN</p>
                <p class="font-semibold">{{ $pendaftar->nisn }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Tempat, Tanggal Lahir</p>
                <p class="font-semibold">{{ $pendaftar->tempat_lahir }}, {{ $pendaftar->tanggal_lahir }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Jenis Kelamin</p>
                <p class="font-semibold">{{ $pendaftar->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Agama</p>
                <p class="font-semibold">{{ $pendaftar->agama }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">No. HP</p>
                <p class="font-semibold">{{ $pendaftar->no_hp_siswa }}</p>
            </div>
        </div>

        <div class="mt-6">
            <p class="text-sm text-gray-600">Alamat</p>
            <p class="font-semibold">{{ $pendaftar->alamat }}</p>
        </div>

        <div class="grid grid-cols-2 gap-6 mt-6">
            <div>
                <p class="text-sm text-gray-600">Sekolah Asal</p>
                <p class="font-semibold">{{ $pendaftar->sekolah_asal }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Tahun Lulus</p>
                <p class="font-semibold">{{ $pendaftar->tahun_lulus }}</p>
            </div>
        </div>

        <div class="mt-6">
            <h3 class="font-semibold mb-2">Data Orang Tua</h3>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-600">Nama Ayah</p>
                    <p class="font-semibold">{{ $pendaftar->nama_ayah }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Nama Ibu</p>
                    <p class="font-semibold">{{ $pendaftar->nama_ibu }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Pekerjaan Orang Tua</p>
                    <p class="font-semibold">{{ $pendaftar->pekerjaan_ortu }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">No. HP Orang Tua</p>
                    <p class="font-semibold">{{ $pendaftar->no_hp_ortu }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
