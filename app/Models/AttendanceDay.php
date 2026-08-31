<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttendanceDay extends Model
{
    protected $fillable = [
        'tanggal',
        'tipe_hari',
        'nama_event',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return ['tanggal' => 'date'];
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(EventAttendanceSchedule::class)->orderBy('urutan')->orderBy('id');
    }
}
