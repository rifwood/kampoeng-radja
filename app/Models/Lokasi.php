<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Lokasi extends Model
{
    public $timestamps = false;

    protected $table = 'lokasi';

    protected $fillable = ['nama_lokasi'];

    public function closingEvents(): BelongsToMany
    {
        return $this->belongsToMany(ClosingEvent::class, 'closing_event_lokasi');
    }
}
