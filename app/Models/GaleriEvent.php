<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GaleriEvent extends Model
{
    protected $table = 'galeri_event';

    protected $guarded = [];

    protected function casts(): array
    {
        return ['tanggal_event' => 'date'];
    }

    public function photos(): HasMany
    {
        return $this->hasMany(GaleriEventFoto::class, 'galeri_event_id');
    }
}
