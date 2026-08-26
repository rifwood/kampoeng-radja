<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomeHero extends Model
{
    protected $table = 'home_hero';

    protected $fillable = [
        'video_path',
        'poster_path',
        'eyebrow',
        'judul',
        'tagline',
        'deskripsi',
        'cta_primary_label',
        'cta_primary_url',
        'cta_secondary_label',
        'cta_secondary_url',
        'updated_by',
    ];

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
