<?php

namespace App\Exports\Attendance;

use App\Models\Absensi;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DailyAttendanceSheet implements FromArray, WithColumnFormatting, WithColumnWidths, WithHeadings, WithStyles, WithTitle
{
    /**
     * @param  Collection<int, Absensi>  $records
     */
    public function __construct(
        private readonly CarbonImmutable $date,
        private readonly Collection $records,
    ) {}

    /**
     * @return list<array<int, int|float|string>>
     */
    public function array(): array
    {
        if ($this->records->isEmpty()) {
            return [['Belum ada data absensi pada tanggal ini.', '', '', '', '', '', '', '']];
        }

        return $this->records
            ->values()
            ->map(fn (Absensi $record, int $index): array => [
                $index + 1,
                $record->tanggal_absensi->locale('id')->translatedFormat('d M Y'),
                $record->karyawan?->nama ?? '-',
                $record->karyawan?->jabatan?->nama_jabatan ?? '-',
                $record->status_kehadiran,
                $this->timeValue($record->jam_masuk),
                $this->timeValue($record->jam_keluar),
                $record->keterangan ?: '-',
            ])
            ->all();
    }

    /**
     * @return list<string>
     */
    public function headings(): array
    {
        return [
            'NO',
            'TANGGAL',
            'NAMA',
            'JABATAN',
            'KEHADIRAN',
            'JAM MASUK',
            'JAM KELUAR',
            'KETERANGAN',
        ];
    }

    public function title(): string
    {
        return $this->date
            ->locale('id')
            ->translatedFormat('d M Y');
    }

    /**
     * @return array<string, string>
     */
    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_TEXT,
        ];
    }

    /**
     * @return array<string, int|float>
     */
    public function columnWidths(): array
    {
        return [
            'A' => 7,
            'B' => 16,
            'C' => 30,
            'D' => 26,
            'E' => 13,
            'F' => 13,
            'G' => 13,
            'H' => 38,
        ];
    }

    /**
     * @return array<int|string, mixed>
     */
    public function styles(Worksheet $sheet): array
    {
        $lastRow = max(2, $this->records->count() + 1);
        $dataRange = "A1:H{$lastRow}";

        $sheet->setShowGridlines(false);
        $sheet->freezePane('A2');
        $sheet->setAutoFilter($dataRange);
        $sheet->getPageSetup()
            ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
            ->setFitToWidth(1)
            ->setFitToHeight(0);
        $sheet->getRowDimension(1)->setRowHeight(24);
        $sheet->getStyle($dataRange)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("A2:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("B2:B{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("E2:G{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("H2:H{$lastRow}")->getAlignment()->setWrapText(true);

        if ($this->records->isEmpty()) {
            $sheet->mergeCells('A2:H2');
            $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A2')->getFont()->setItalic(true)->getColor()->setRGB('64748B');
        }

        return [
            $dataRange => [
                'font' => [
                    'name' => 'Calibri',
                    'size' => 10,
                    'color' => ['rgb' => '334155'],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'DCE3ED'],
                    ],
                ],
            ],
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => '1E3A8A'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'DBEAFE'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    private function timeValue(?string $time): string
    {
        return $time === null ? '-' : substr($time, 0, 5);
    }
}
