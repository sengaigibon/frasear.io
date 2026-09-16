<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    use HasFactory;

    public const int ANONYMOUS_AUTHOR_ID = 1;
    
    protected $fillable = ['name'];

    public function phrases(): HasMany
    {
        return $this->hasMany(Phrase::class);
    }
}