<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'event_date'];
    protected function casts(): array { return ['event_date' => 'date']; }
    public function photos(): HasMany { return $this->hasMany(EventPhoto::class); }
}
