<?php

namespace App\Exports;

use App\Models\CalonSiswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

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
            'NO_PESERTA',
            'NISN',
            'NAMA',
            'TEMPAT_LAHIR',
            'TANGGAL_LAHIR',
            'JK',
            'AGAMA',
            'ALAMAT',
            'NO_HP',
            'SEKOLAH_ASAL',
            'TAHUN_LULUS',
            'AYAH',
            'IBU',
            'PEKERJAAN_ORTU',
            'NO_HP_ORTU',
            'TAHUN_AJARAN',
            'TGL_DAFTAR'
        ];
    }

    public function map($siswa): array
    {
        return [
            $siswa->no_peserta,
            $siswa->nisn,
            $siswa->nama_lengkap,
            $siswa->tempat_lahir,
            $siswa->tanggal_lahir,
            $siswa->jenis_kelamin,
            $siswa->agama,
            $siswa->alamat,
            $siswa->no_hp_siswa,
            $siswa->sekolah_asal,
            $siswa->tahun_lulus,
            $siswa->nama_ayah,
            $siswa->nama_ibu,
            $siswa->pekerjaan_ortu,
            $siswa->no_hp_ortu,
            $siswa->tahunAjaran->tahun_ajaran ?? '-',
            $siswa->created_at->format('Y-m-d')
        ];
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';', // Untuk Excel Indonesia biasanya pakai ;
            'enclosure' => '"',
            'line_ending' => "\r\n",
            'use_bom' => true, // Supaya bisa membaca huruf Indonesia
        ];
    }
}
