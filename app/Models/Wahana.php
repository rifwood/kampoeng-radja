<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wahana extends Model
{
    public const LABELS = [
        'Air',
        'Darat',
        'Anak-anak',
        'Dewasa',
        'Adrenaline',
        'Santai',
    ];

    protected $table = 'wahana';

    protected $fillable = [
        'created_by',
        'updated_by',
        'nama_wahana',
        'deskripsi_singkat',
        'foto',
        'label',
        'is_unggulan',
        'is_active',
        'urutan_tampil',
    ];

    protected function casts(): array
    {
        return [
            'is_unggulan' => 'boolean',
            'is_active' => 'boolean',
            'urutan_tampil' => 'integer',
        ];
    }

    /**
     * @return list<string>
     */
    public function labels(): array
    {
        return collect(explode(',', (string) $this->label))
            ->map(fn (string $label): string => trim($label))
            ->filter()
            ->unique(fn (string $label): string => mb_strtolower($label))
            ->values()
            ->all();
    }

    public function fotos(): HasMany
    {
        return $this->hasMany(WahanaFoto::class)
            ->orderBy('urutan')
            ->orderBy('id');
    }
}
