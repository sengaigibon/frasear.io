<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Phrase extends Model
{
    use HasFactory;

    protected $fillable = ['body', 'author_id'];

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    // Users who saved this phrase — distinct from who wrote it (author).
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_phrases')
            ->withPivot('created_at'); // pivot has created_at but no updated_at
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'phrases_tags');
    }
}