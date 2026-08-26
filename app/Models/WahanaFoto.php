<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WahanaFoto extends Model
{
    protected $table = 'wahana_foto';

    protected $fillable = [
        'wahana_id',
        'foto',
        'urutan',
    ];

    protected function casts(): array
    {
        return [
            'urutan' => 'integer',
        ];
    }

    public function wahana(): BelongsTo
    {
        return $this->belongsTo(Wahana::class);
    }
}
