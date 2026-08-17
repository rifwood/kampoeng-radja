<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wahana extends Model
{
    protected $table = 'wahana';

    protected $guarded = [];

    protected function casts(): array
    {
        return ['is_unggulan' => 'boolean'];
    }
}
