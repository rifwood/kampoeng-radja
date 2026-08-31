<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';

    protected $fillable = [
        'nama',
        'deskripsi_singkat',
        'deskripsi_lengkap',
        'thumbnail',
        'hero_image',
        'is_active',
        'urutan_tampil',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'urutan_tampil' => 'integer'];
    }
}
