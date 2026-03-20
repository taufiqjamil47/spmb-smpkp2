<?php

namespace App\Exports;

use App\Models\CalonSiswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class CalonSiswaExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithEvents
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
            'NO PESERTA',
            'NISN',
            'NIK',
            'NAMA LENGKAP',
            'TEMPAT LAHIR',
            'TANGGAL LAHIR',
            'JENIS KELAMIN',
            'AGAMA',
            'ALAMAT',
            'RT',
            'RW',
            'DESA',
            'KECAMATAN',
            'NO HP SISWA',
            'NO TELP',
            'SEKOLAH ASAL',
            'TAHUN LULUS',
            'TINGGI BADAN',
            'BERAT BADAN',
            'ANAK KE',
            'UKURAN BAJU',
            'PKH',
            'KKS',
            'PIP',
            'NAMA AYAH',
            'TAHUN LAHIR AYAH',
            'PEKERJAAN AYAH',
            'PENDIDIKAN AYAH',
            'NAMA IBU',
            'TAHUN LAHIR IBU',
            'PEKERJAAN IBU',
            'PENDIDIKAN IBU',
            'NAMA WALI',
            'TAHUN LAHIR WALI',
            'PEKERJAAN WALI',
            'PENDIDIKAN WALI',
            'NO HP ORTU',
            'TAHUN AJARAN',
            'TANGGAL DAFTAR',
            'STATUS',
            'TANGGAL DIHAPUS'
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
            $siswa->nisn,
            $siswa->nik,
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

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 11],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2563EB']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();

                // Border untuk semua sel
                $sheet->getStyle('A1:' . $lastColumn . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'CCCCCC']
                        ]
                    ]
                ]);

                // Warna baris untuk status dihapus
                for ($row = 2; $row <= $lastRow; $row++) {
                    $status = $sheet->getCell('AP' . $row)->getValue();
                    if ($status == 'DIHAPUS') {
                        $sheet->getStyle('A' . $row . ':' . $lastColumn . $row)
                            ->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setRGB('FEE2E2');
                    }
                }
            }
        ];
    }
}
