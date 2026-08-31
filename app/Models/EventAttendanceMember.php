<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventAttendanceMember extends Model
{
    protected $fillable = [
        'attendance_day_id',
        'event_attendance_schedule_id',
        'karyawan_id',
    ];

    public function attendanceDay(): BelongsTo
    {
        return $this->belongsTo(AttendanceDay::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(EventAttendanceSchedule::class, 'event_attendance_schedule_id');
    }

    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class);
    }
}
