<?php

namespace App\Exports\Attendance;

use App\Models\Absensi;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MonthlyAttendanceExport implements WithMultipleSheets
{
    /**
     * @param  Collection<int, Absensi>  $records
     */
    public function __construct(
        private readonly Collection $records,
        private readonly CarbonImmutable $period,
    ) {}

    /**
     * @return list<MonthlyAttendanceSummarySheet|DailyAttendanceSheet>
     */
    public function sheets(): array
    {
        $recordsByDate = $this->records
            ->groupBy(fn (Absensi $record): string => $record->tanggal_absensi->toDateString())
            ->map(fn (Collection $dailyRecords): Collection => $dailyRecords
                ->sortBy(fn (Absensi $record): string => mb_strtolower($record->karyawan?->nama ?? ''))
                ->values());

        $sheets = [new MonthlyAttendanceSummarySheet($this->records)];
        $date = $this->period->startOfMonth();
        $lastDate = $this->period->endOfMonth();

        while ($date->lessThanOrEqualTo($lastDate)) {
            $sheets[] = new DailyAttendanceSheet(
                $date,
                $recordsByDate->get($date->toDateString(), collect()),
            );
            $date = $date->addDay();
        }

        return $sheets;
    }
}
