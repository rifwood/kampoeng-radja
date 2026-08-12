<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class WahanaPhoto extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'photo_path', 'alt_text', 'is_featured'];
    protected function casts(): array { return ['is_featured' => 'boolean']; }
    public function labels(): BelongsToMany { return $this->belongsToMany(Label::class); }
}
