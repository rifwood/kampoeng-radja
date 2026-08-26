<?php

namespace App\Exports\Attendance;

use App\Models\Absensi;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MonthlyAttendanceSummarySheet implements FromArray, WithColumnWidths, WithHeadings, WithStyles, WithTitle
{
    /**
     * @param  Collection<int, Absensi>  $records
     */
    public function __construct(private readonly Collection $records) {}

    /**
     * @return list<array<int, int|string>>
     */
    public function array(): array
    {
        return $this->records
            ->groupBy('karyawan_id')
            ->map(function (Collection $employeeRecords): array {
                /** @var Absensi $firstRecord */
                $firstRecord = $employeeRecords->first();

                return [
                    0,
                    $firstRecord->karyawan?->nama ?? '-',
                    $firstRecord->karyawan?->jabatan?->nama_jabatan ?? '-',
                    $employeeRecords->where('status_kehadiran', 'H')->count(),
                    $employeeRecords->where('status_kehadiran', 'I')->count(),
                    $employeeRecords->where('status_kehadiran', 'A')->count(),
                    $employeeRecords->filter(fn (Absensi $record): bool => $this->isLate($record))->count(),
                    $employeeRecords->filter(fn (Absensi $record): bool => $this->isEarlyLeave($record))->count(),
                ];
            })
            ->sortBy(fn (array $row): string => mb_strtolower((string) $row[1]))
            ->values()
            ->map(function (array $row, int $index): array {
                $row[0] = $index + 1;

                return $row;
            })
            ->all();
    }

    /**
     * @return list<string>
     */
    public function headings(): array
    {
        return [
            'NO',
            'NAMA KARYAWAN',
            'JABATAN',
            'TOTAL HADIR',
            'TOTAL IZIN',
            'TOTAL ALFA',
            'TOTAL TERLAMBAT',
            'TOTAL PULANG AWAL',
        ];
    }

    public function title(): string
    {
        return 'Rekap Bulanan';
    }

    /**
     * @return array<string, int|float>
     */
    public function columnWidths(): array
    {
        return [
            'A' => 7,
            'B' => 30,
            'C' => 26,
            'D' => 15,
            'E' => 14,
            'F' => 14,
            'G' => 18,
            'H' => 20,
        ];
    }

    /**
     * @return array<int|string, mixed>
     */
    public function styles(Worksheet $sheet): array
    {
        $lastRow = $this->records->pluck('karyawan_id')->unique()->count() + 1;
        $dataRange = "A1:H{$lastRow}";

        $sheet->setShowGridlines(false);
        $sheet->freezePane('A2');
        $sheet->setAutoFilter($dataRange);
        $sheet->getRowDimension(1)->setRowHeight(24);
        $sheet->getStyle($dataRange)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        if ($lastRow > 1) {
            $sheet->getStyle("A2:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D2:H{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
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

    private function isLate(Absensi $record): bool
    {
        return $record->status_kehadiran === 'H'
            && $record->jam_masuk !== null
            && substr($record->jam_masuk, 0, 5) > '08:30';
    }

    private function isEarlyLeave(Absensi $record): bool
    {
        return $record->status_kehadiran === 'H'
            && $record->jam_keluar !== null
            && substr($record->jam_keluar, 0, 5) < '16:30';
    }
}
