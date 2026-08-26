<?php

namespace App\Exports\ClosingEvent;

use App\Models\ClosingEvent;
use Carbon\CarbonInterface;
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

class ClosingEventsExport extends DefaultValueBinder implements FromArray, WithColumnFormatting, WithColumnWidths, WithCustomValueBinder, WithHeadings, WithStyles, WithTitle
{
    /**
     * @param  Collection<int, ClosingEvent>  $events
     */
    public function __construct(private readonly Collection $events) {}

    /**
     * @return list<array<int, float|int|string>>
     */
    public function array(): array
    {
        return $this->events
            ->values()
            ->map(fn (ClosingEvent $event, int $index): array => [
                $index + 1,
                $this->dateValue($event->tanggal),
                $this->dateValue($event->tanggal_selesai),
                $event->pic?->nama_pic ?? '-',
                $event->konsumen,
                $event->kontak,
                $event->jenisEvent?->jenis_event ?? '-',
                $event->lokasi->pluck('nama_lokasi')->implode(', ') ?: '-',
                $this->timeValue($event->jam_kedatangan),
                $event->jumlah_pengunjung,
                $event->konsumsi ? 'Ya' : 'Tidak',
                $event->additional ?: '-',
                $event->panitia ?: '-',
                (float) $event->harga_total,
                mb_strtoupper($event->status_event),
                $event->alasan_pembatalan ?: '-',
                $event->canceller?->karyawan?->nama ?? $event->canceller?->username ?? '-',
                $event->cancelled_at?->locale('id')->translatedFormat('d F Y, H:i') ?? '-',
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
            'TANGGAL MULAI',
            'TANGGAL SELESAI',
            'PIC',
            'KONSUMEN',
            'KONTAK',
            'JENIS EVENT',
            'LOKASI',
            'JAM KEDATANGAN',
            'JUMLAH PENGUNJUNG',
            'KONSUMSI',
            'ADDITIONAL',
            'PANITIA',
            'HARGA TOTAL',
            'STATUS EVENT',
            'ALASAN PEMBATALAN',
            'DIBATALKAN OLEH',
            'DIBATALKAN PADA',
        ];
    }

    public function title(): string
    {
        return 'Closing Event';
    }

    public function bindValue(Cell $cell, mixed $value): bool
    {
        if ($cell->getColumn() === 'F') {
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
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
            'I' => NumberFormat::FORMAT_TEXT,
            'N' => '[$Rp-421] #,##0',
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
            'C' => 16,
            'D' => 20,
            'E' => 28,
            'F' => 18,
            'G' => 30,
            'H' => 40,
            'I' => 18,
            'J' => 21,
            'K' => 13,
            'L' => 42,
            'M' => 42,
            'N' => 22,
            'O' => 18,
            'P' => 42,
            'Q' => 24,
            'R' => 24,
        ];
    }

    /**
     * @return array<int|string, mixed>
     */
    public function styles(Worksheet $sheet): array
    {
        $lastRow = $this->events->count() + 1;
        $dataRange = "A1:R{$lastRow}";

        $sheet->setShowGridlines(false);
        $sheet->freezePane('A2');
        $sheet->setAutoFilter($dataRange);
        $sheet->getPageSetup()
            ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
            ->setFitToWidth(1)
            ->setFitToHeight(0);
        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getStyle($dataRange)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle("A2:D{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("I2:K{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("N2:N{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("O2:O{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("H2:H{$lastRow}")->getAlignment()->setWrapText(true);
        $sheet->getStyle("L2:M{$lastRow}")->getAlignment()->setWrapText(true);
        $sheet->getStyle("P2:R{$lastRow}")->getAlignment()->setWrapText(true);

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

    private function timeValue(?string $time): string
    {
        return $time === null || $time === '' ? '-' : substr($time, 0, 5);
    }

    private function dateValue(?CarbonInterface $date): string
    {
        if (! $date) {
            return '-';
        }

        $month = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
        ][$date->month];

        return $date->format('d').' '.$month.' '.$date->year;
    }
}
