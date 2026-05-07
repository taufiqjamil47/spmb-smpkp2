<?php

namespace App\Exports;

use App\Models\CalonSiswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CalonSiswaCsvExport implements FromCollection, WithHeadings, WithMapping, WithCustomCsvSettings
{
    protected $tahunAjaranId;
    protected $status;

    public function __construct($tahunAjaranId = null, $status = null)
    {
        $this->tahunAjaranId = $tahunAjaranId;
        $this->status = $status;
    }

    public function collection()
    {
        $query = CalonSiswa::with('tahunAjaran');

        if ($this->tahunAjaranId) {
            $query->where('tahun_ajaran_id', $this->tahunAjaranId);
        }

        if ($this->status == 'trash') {
            $query->onlyTrashed();
        } elseif ($this->status == 'all') {
            $query->withTrashed();
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'NO',
            'NO_PESERTA',
            'NISN',
            'NIK',
            'NAMA_LENGKAP',
            'TEMPAT_LAHIR',
            'TANGGAL_LAHIR',
            'JENIS_KELAMIN',
            'AGAMA',
            'ALAMAT',
            'RT',
            'RW',
            'DESA',
            'KECAMATAN',
            'NO_HP_SISWA',
            'NO_TELP',
            'SEKOLAH_ASAL',
            'TAHUN_LULUS',
            'TINGGI_BADAN',
            'BERAT_BADAN',
            'ANAK_KE',
            'UKURAN_BAJU',
            'PKH',
            'KKS',
            'PIP',
            'NAMA_AYAH',
            'TAHUN_LAHIR_AYAH',
            'PEKERJAAN_AYAH',
            'PENDIDIKAN_AYAH',
            'NAMA_IBU',
            'TAHUN_LAHIR_IBU',
            'PEKERJAAN_IBU',
            'PENDIDIKAN_IBU',
            'NAMA_WALI',
            'TAHUN_LAHIR_WALI',
            'PEKERJAAN_WALI',
            'PENDIDIKAN_WALI',
            'NO_HP_ORTU',
            'TAHUN_AJARAN',
            'TANGGAL_DAFTAR',
            'STATUS',
            'TANGGAL_DIHAPUS'
        ];
    }

    public function map($siswa): array
    {
        static $rowNumber = 0;
        $rowNumber++;

        $jk = $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan';
        $status = $siswa->trashed() ? 'DIHAPUS' : 'AKTIF';

        return [
            $rowNumber,
            $siswa->no_peserta,
            $siswa->nisn . "\t", // Tambahkan \t untuk mencegah konversi otomatis
            $siswa->nik . "\t",   // Tambahkan \t untuk mencegah konversi otomatis
            $siswa->nama_lengkap,
            $siswa->tempat_lahir,
            $siswa->tanggal_lahir,
            $jk,
            $siswa->agama,
            $siswa->alamat,
            $siswa->rt,
            $siswa->rw,
            $siswa->desa,
            $siswa->kecamatan,
            $siswa->no_hp_siswa,
            $siswa->no_telp,
            $siswa->sekolah_asal,
            $siswa->tahun_lulus,
            $siswa->tinggi_badan,
            $siswa->berat_badan,
            $siswa->anak_ke,
            $siswa->ukuran_baju,
            $siswa->pkh,
            $siswa->kks,
            $siswa->pip,
            $siswa->nama_ayah,
            $siswa->tahun_lahir_ayah,
            $siswa->pekerjaan_ayah,
            $siswa->pendidikan_ayah,
            $siswa->nama_ibu,
            $siswa->tahun_lahir_ibu,
            $siswa->pekerjaan_ibu,
            $siswa->pendidikan_ibu,
            $siswa->nama_wali,
            $siswa->tahun_lahir_wali,
            $siswa->pekerjaan_wali,
            $siswa->pendidikan_wali,
            $siswa->no_hp_ortu,
            $siswa->tahunAjaran->tahun_ajaran ?? '-',
            $siswa->created_at->format('d/m/Y H:i'),
            $status,
            $siswa->trashed() ? $siswa->deleted_at->format('d/m/Y H:i') : '-'
        ];
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';',      // Untuk Excel Indonesia biasanya pakai ;
            'enclosure' => '"',       // Pembungkus teks
            'line_ending' => "\r\n",  // Line ending Windows
            'use_bom' => true,        // UTF-8 BOM untuk huruf Indonesia
            'output_encoding' => 'UTF-8', // Encoding UTF-8
            'sheet_writing' => true,   // Mode sheet writing
        ];
    }
}
