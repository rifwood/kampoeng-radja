<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pic extends Model
{
    public $timestamps = false;

    protected $table = 'pic';

    protected $fillable = ['nama_pic'];

    public function closingEvents(): HasMany
    {
        return $this->hasMany(ClosingEvent::class);
    }
}
