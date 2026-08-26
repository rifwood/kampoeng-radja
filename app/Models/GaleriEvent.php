<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GaleriEvent extends Model
{
    protected $table = 'galeri_event';

    protected $fillable = [
        'nama_event',
        'deskripsi',
        'tanggal_event',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return ['tanggal_event' => 'date'];
    }

    public function photos(): HasMany
    {
        return $this->hasMany(GaleriEventFoto::class, 'galeri_event_id')
            ->orderByRaw('CASE WHEN urutan IS NULL THEN 1 ELSE 0 END')
            ->orderBy('urutan')
            ->orderBy('id');
    }
}
