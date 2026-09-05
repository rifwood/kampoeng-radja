<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TempatMakanFoto extends Model
{
    protected $table = 'tempat_makan_foto';

    protected $fillable = ['tempat_makan_id', 'foto', 'urutan'];

    protected function casts(): array
    {
        return ['urutan' => 'integer'];
    }

    public function tempatMakan(): BelongsTo
    {
        return $this->belongsTo(TempatMakan::class);
    }
}
