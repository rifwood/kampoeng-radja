<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventPromo extends Model
{
    protected $table = 'event_promo';

    protected $fillable = [
        'created_by',
        'updated_by',
        'judul',
        'deskripsi_singkat',
        'poster',
        'link_wa',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
