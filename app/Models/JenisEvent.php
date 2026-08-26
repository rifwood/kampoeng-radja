<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisEvent extends Model
{
    public $timestamps = false;

    protected $table = 'event';

    protected $fillable = ['jenis_event'];

    public function closingEvents(): HasMany
    {
        return $this->hasMany(ClosingEvent::class, 'event_id');
    }
}
