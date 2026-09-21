<?php

namespace App\Http\Controllers;

use App\Models\Phrase;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PhraseSearchController extends Controller
{
    public function index(Request $request): View
    {
        $mode = $this->resolveMode($request);
        $username = trim((string) $request->query('username', ''));
        $term = $this->normalizeTerm((string) $request->query('q', $request->query('tag', '')), $mode);

        $query = Phrase::query()->whereHas('users');

        if ($username !== '') {
            $query->whereHas('users', fn ($userQuery) => $userQuery->where('username', $username));
        }

        if ($term === '') {
            return view('phrases.search', [
                'term' => '',
                'mode' => $mode,
                'username' => $username,
                'phrases' => null,
            ]);
        }

        $query = match ($mode) {
            'author' => $this->searchByAuthor($query, $term),
            'tag' => $this->searchByTag($query, $term),
            default => $this->searchByBody($query, $term),
        };

        $phrases = $query
            ->with(['author', 'tags', 'users'])
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString()
            ->through(function (Phrase $phrase) {
                $phrase->setRelation('savedBy', $phrase->users->first());

                return $phrase;
            });

        return view('phrases.search', [
            'term' => $term,
            'mode' => $mode,
            'username' => $username,
            'phrases' => $phrases,
        ]);
    }

    private function resolveMode(Request $request): string
    {
        $mode = trim((string) $request->query('mode', ''));

        if ($mode !== '') {
            return $mode;
        }

        if ($request->query('tag') !== null) {
            return 'tag';
        }

        return 'word';
    }

    private function normalizeTerm(string $term, string $mode): string
    {
        $sanitized = preg_replace('/\s+/', ' ', trim($term)) ?? '';
        $parts = preg_split('/\s+/', $sanitized, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        if ($mode === 'word') {
            return $parts[0] ?? '';
        }

        if (count($parts) > 2) {
            return implode(' ', array_slice($parts, 0, 2));
        }

        return implode(' ', $parts);
    }

    private function searchByBody(Builder $query, string $term): Builder
    {
        return $query->where('body', 'ilike', "%{$term}%");
    }

    private function searchByAuthor(Builder $query, string $term): Builder
    {
        $words = preg_split('/\s+/', $term, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        return $query->whereHas('author', function ($authorQuery) use ($words) {
            foreach ($words as $word) {
                $authorQuery->where('name', 'ilike', "%{$word}%");
            }
        });
    }

    private function searchByTag(Builder $query, string $term): Builder
    {
        $words = preg_split('/\s+/', $term, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        return $query->whereHas('tags', function ($tagQuery) use ($words) {
            $tagQuery->where(function ($tagClause) use ($words) {
                foreach ($words as $word) {
                    $tagClause->orWhere('name', 'ilike', "%{$word}%");
                }
            });
        });
    }
}
