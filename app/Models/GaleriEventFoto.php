<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GaleriEventFoto extends Model
{
    protected $table = 'galeri_event_foto';

    protected $fillable = [
        'galeri_event_id',
        'foto',
        'caption',
        'urutan',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return ['urutan' => 'integer'];
    }

    public function galeriEvent(): BelongsTo
    {
        return $this->belongsTo(GaleriEvent::class, 'galeri_event_id');
    }
}
