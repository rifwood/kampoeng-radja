<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MediaBerita extends Model
{
    protected $table = 'media_berita';

    protected $fillable = [
        'created_by',
        'updated_by',
        'judul',
        'deskripsi',
        'foto',
        'tanggal_publish',
    ];

    protected function casts(): array
    {
        return ['tanggal_publish' => 'datetime'];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
