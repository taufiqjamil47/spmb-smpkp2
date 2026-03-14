<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Peserta - {{ $pendaftar->no_peserta }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body {
                margin: 0;
                padding: 20px;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body class="bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8 border-2 border-blue-500">
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-blue-800">KARTU PESERTA</h1>
                <h2 class="text-xl">PENERIMAAN SISWA BARU</h2>
                <p class="text-gray-600">TAHUN AJARAN {{ $pendaftar->tahunAjaran->tahun_ajaran }}</p>
            </div>

            <div class="border-t-2 border-b-2 border-blue-300 py-4 mb-4">
                <p class="text-center text-2xl font-mono font-bold">{{ $pendaftar->no_peserta }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Nama Lengkap</p>
                    <p class="font-semibold text-lg">{{ $pendaftar->nama_lengkap }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">NISN</p>
                    <p class="font-semibold">{{ $pendaftar->nisn }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tempat, Tgl Lahir</p>
                    <p class="font-semibold">{{ $pendaftar->tempat_lahir }},
                        {{ $pendaftar->tanggal_lahir }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Jenis Kelamin</p>
                    <p class="font-semibold">{{ $pendaftar->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Sekolah Asal</p>
                    <p class="font-semibold">{{ $pendaftar->sekolah_asal }}</p>
                </div>
            </div>

            <div class="mt-8 text-center text-sm text-gray-500">
                <p>Kartu ini berlaku sebagai bukti pendaftaran</p>
                <p class="mt-4">Bandung, {{ date('d F Y') }}</p>
                <p class="mt-8">Kepala Sekolah</p>
                <p class="mt-12">_________________________</p>
                <p class="text-xs mt-2">NIP. 123456789</p>
            </div>
        </div>

        <div class="text-center mt-4 no-print">
            <button onclick="window.print()" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                <i class="fas fa-print mr-2"></i>Cetak Kartu
            </button>
            <a href="{{ route('pendaftaran.show', $pendaftar->id) }}"
                class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600 ml-2">
                Kembali
            </a>
        </div>
    </div>
</body>

</html>
