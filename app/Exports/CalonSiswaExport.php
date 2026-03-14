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
use PhpOffice\PhpSpreadsheet\Style\Color;
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

    /**
     * @return \Illuminate\Support\Collection
     */
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

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'NO',
            'NO PESERTA',
            'NISN',
            'NAMA LENGKAP',
            'TEMPAT LAHIR',
            'TANGGAL LAHIR',
            'JENIS KELAMIN',
            'AGAMA',
            'ALAMAT',
            'NO HP SISWA',
            'SEKOLAH ASAL',
            'TAHUN LULUS',
            'NAMA AYAH',
            'NAMA IBU',
            'PEKERJAAN ORTU',
            'NO HP ORTU',
            'TAHUN AJARAN',
            'TANGGAL DAFTAR',
            'STATUS',
            'TANGGAL DIHAPUS'
        ];
    }

    /**
     * @param mixed $siswa
     * @return array
     */
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
            $siswa->nama_lengkap,
            $siswa->tempat_lahir,
            $siswa->tanggal_lahir,
            $jk,
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
            $siswa->created_at->format('d/m/Y H:i'),
            $status,
            $siswa->trashed() ? $siswa->deleted_at->format('d/m/Y H:i') : '-'
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4B5563']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ],
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Style untuk header
                $sheet->getStyle('A1:T1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '2563EB']
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                // Style untuk sel data
                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();

                $sheet->getStyle('A2:' . $lastColumn . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'CCCCCC']
                        ]
                    ]
                ]);

                // Warna baris untuk status
                for ($row = 2; $row <= $lastRow; $row++) {
                    $status = $sheet->getCell('S' . $row)->getValue();
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
