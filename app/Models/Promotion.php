<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'image', 'whatsapp_number', 'start_date', 'end_date'];
    protected function casts(): array { return ['start_date' => 'date', 'end_date' => 'date']; }
}
