<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TempatMakanMenuHighlight extends Model
{
    protected $table = 'tempat_makan_menu_highlight';

    protected $fillable = ['tempat_makan_id', 'nama_menu', 'urutan'];

    protected function casts(): array
    {
        return ['urutan' => 'integer'];
    }

    public function tempatMakan(): BelongsTo
    {
        return $this->belongsTo(TempatMakan::class);
    }
}
