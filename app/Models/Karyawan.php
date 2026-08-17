<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Karyawan extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'karyawan';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'tanggal_masuk' => 'date',
            'tanggal_keluar' => 'date',
        ];
    }

    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class);
    }

    public function departemen(): BelongsTo
    {
        return $this->belongsTo(Departemen::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }
}
