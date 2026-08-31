<?php

namespace App\Exports\Employee;

use App\Models\Karyawan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EmployeesExport extends DefaultValueBinder implements FromArray, WithColumnFormatting, WithColumnWidths, WithCustomValueBinder, WithHeadings, WithStyles, WithTitle
{
    /**
     * @param  Collection<int, Karyawan>  $employees
     */
    public function __construct(
        private readonly Collection $employees,
        private readonly string $activeStatus,
    ) {}

    /**
     * @return list<array<int, int|string>>
     */
    public function array(): array
    {
        return $this->employees
            ->values()
            ->map(fn (Karyawan $employee, int $index): array => [
                $index + 1,
                $employee->nama,
                $employee->nik,
                $this->genderLabel($employee->jenis_kelamin),
                str($employee->agama)->title()->toString(),
                $employee->tempat_lahir,
                $this->dateLabel($employee->tanggal_lahir),
                $employee->alamat,
                str($employee->status_perkawinan)->title()->toString(),
                $employee->pendidikan,
                $employee->jabatan?->nama_jabatan ?? '-',
                $employee->departemen?->nama_departemen ?? '-',
                $employee->penempatan?->nama_penempatan ?? '-',
                $employee->atasanLangsung?->nama ?? '-',
                str($employee->status_kerja)->title()->toString(),
                str($employee->status_keaktifan)->title()->toString(),
                $this->dateLabel($employee->tanggal_masuk),
                $this->dateLabel($employee->tanggal_keluar),
                $employee->no_hp,
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
            'NAMA',
            'NIK',
            'JENIS KELAMIN',
            'AGAMA',
            'TEMPAT LAHIR',
            'TANGGAL LAHIR',
            'ALAMAT',
            'STATUS PERKAWINAN',
            'PENDIDIKAN',
            'JABATAN',
            'DEPARTEMEN',
            'PENEMPATAN',
            'ATASAN LANGSUNG',
            'STATUS KERJA',
            'STATUS KEAKTIFAN',
            'TANGGAL MASUK',
            'TANGGAL KELUAR',
            'NO HP',
        ];
    }

    public function title(): string
    {
        return 'Karyawan '.str($this->activeStatus)->title();
    }

    public function bindValue(Cell $cell, mixed $value): bool
    {
        if (in_array($cell->getColumn(), ['C', 'S'], true)) {
            $cell->setValueExplicit((string) $value, DataType::TYPE_STRING);

            return true;
        }

        return parent::bindValue($cell, $value);
    }

    /**
     * @return array<string, string>
     */
    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT,
            'S' => NumberFormat::FORMAT_TEXT,
        ];
    }

    /**
     * @return array<string, int|float>
     */
    public function columnWidths(): array
    {
        return [
            'A' => 7,
            'B' => 28,
            'C' => 20,
            'D' => 18,
            'E' => 16,
            'F' => 22,
            'G' => 18,
            'H' => 42,
            'I' => 23,
            'J' => 15,
            'K' => 26,
            'L' => 27,
            'M' => 24,
            'N' => 28,
            'O' => 18,
            'P' => 20,
            'Q' => 18,
            'R' => 18,
            'S' => 19,
        ];
    }

    /**
     * @return array<int|string, mixed>
     */
    public function styles(Worksheet $sheet): array
    {
        $lastRow = $this->employees->count() + 1;
        $dataRange = "A1:S{$lastRow}";

        $sheet->setShowGridlines(false);
        $sheet->freezePane('A2');
        $sheet->setAutoFilter($dataRange);
        $sheet->getPageSetup()
            ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
            ->setFitToWidth(1)
            ->setFitToHeight(0);
        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getStyle($dataRange)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("A2:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("C2:G{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("I2:S{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("H2:H{$lastRow}")->getAlignment()->setWrapText(true);

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
                    'wrapText' => true,
                ],
            ],
        ];
    }

    private function dateLabel(mixed $date): string
    {
        return $date === null
            ? '-'
            : $date->locale('id')->translatedFormat('d F Y');
    }

    private function genderLabel(string $gender): string
    {
        return match ($gender) {
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
            default => $gender,
        };
    }
}
