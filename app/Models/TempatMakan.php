<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TempatMakan extends Model
{
    public const CATEGORIES = ['Resto', 'Café', 'Saung', 'Minuman', 'Camilan'];

    protected $table = 'tempat_makan';

    protected $fillable = [
        'nama',
        'kategori',
        'tagline',
        'deskripsi',
        'jam_buka',
        'jam_tutup',
        'kapasitas',
        'lokasi',
        'jenis_menu',
        'is_recommended',
        'is_active',
        'urutan_tampil',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'kapasitas' => 'integer',
            'is_recommended' => 'boolean',
            'is_active' => 'boolean',
            'urutan_tampil' => 'integer',
        ];
    }

    public function photos(): HasMany
    {
        return $this->hasMany(TempatMakanFoto::class)->orderBy('urutan')->orderBy('id');
    }

    public function menuHighlights(): HasMany
    {
        return $this->hasMany(TempatMakanMenuHighlight::class)->orderBy('urutan')->orderBy('id');
    }
}
