<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    public $timestamps = false; // tags table has no updated_at, created_at only

    protected $fillable = ['name'];

    public function phrases(): BelongsToMany
    {
        return $this->belongsToMany(Phrase::class, 'phrases_tags');
    }
}