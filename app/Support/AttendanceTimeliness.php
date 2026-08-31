<?php

namespace App\Support;

use App\Models\AttendanceDay;

class AttendanceTimeliness
{
    public const NORMAL_ENTRY_TIME = '08:30';

    public const NORMAL_TOLERANCE_MINUTES = 10;

    public const EVENT_TOLERANCE_MINUTES = 5;

    /**
     * @return array{type: 'committee'|'normal', expectedTime: string, toleranceMinutes: int}
     */
    public function scheduleFor(?AttendanceDay $day, int $employeeId): array
    {
        if ($day?->tipe_hari === 'event') {
            $schedule = $day->schedules->first(
                fn ($schedule): bool => $schedule->members->contains('karyawan_id', $employeeId),
            );

            if ($schedule) {
                return [
                    'type' => 'committee',
                    'expectedTime' => substr((string) $schedule->jam_masuk, 0, 5),
                    'toleranceMinutes' => self::EVENT_TOLERANCE_MINUTES,
                ];
            }
        }

        return [
            'type' => 'normal',
            'expectedTime' => self::NORMAL_ENTRY_TIME,
            'toleranceMinutes' => self::NORMAL_TOLERANCE_MINUTES,
        ];
    }

    public function calculate(?string $attendanceStatus, ?string $actualTime, string $expectedTime, int $toleranceMinutes): ?string
    {
        if ($attendanceStatus !== 'H' || ! $actualTime) {
            return null;
        }

        $actualMinutes = $this->minutes(substr($actualTime, 0, 5));
        $expectedMinutes = $this->minutes($expectedTime);

        if ($actualMinutes <= $expectedMinutes) {
            return 'on_time';
        }

        if ($actualMinutes <= $expectedMinutes + $toleranceMinutes) {
            return 'within_tolerance';
        }

        return 'late';
    }

    private function minutes(string $time): int
    {
        [$hours, $minutes] = array_map('intval', explode(':', $time));

        return ($hours * 60) + $minutes;
    }
}
