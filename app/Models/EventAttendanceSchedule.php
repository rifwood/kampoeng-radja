<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventAttendanceSchedule extends Model
{
    protected $fillable = [
        'attendance_day_id',
        'jam_masuk',
        'toleransi_menit',
        'urutan',
    ];

    public function attendanceDay(): BelongsTo
    {
        return $this->belongsTo(AttendanceDay::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(EventAttendanceMember::class);
    }
}
