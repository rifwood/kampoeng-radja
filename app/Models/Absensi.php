<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absensi extends Model
{
    protected $table = 'absensi';

    protected $fillable = [
        'karyawan_id',
        'tanggal_absensi',
        'status_kehadiran',
        'jam_masuk',
        'jam_keluar',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_absensi' => 'date',
        ];
    }

    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class);
    }
}
