<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jabatan extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'jabatan';

    protected $fillable = ['nama_jabatan'];

    public function karyawan(): HasMany
    {
        return $this->hasMany(Karyawan::class);
    }
}
