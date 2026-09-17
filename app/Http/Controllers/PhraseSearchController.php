<?php

namespace App\Http\Controllers;

use App\Models\Phrase;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PhraseSearchController extends Controller
{
    public function index(Request $request): View
    {
        $term = trim((string) $request->query('tag', ''));

        $phrases = $term === ''
            ? null
            : Phrase::query()
                ->whereHas('tags', fn ($q) => $q->where('name', 'ilike', "%{$term}%"))
                ->whereHas('users')
                ->with(['author', 'tags', 'users'])
                ->orderByDesc('created_at')
                ->paginate(15)
                ->withQueryString()
                ->through(function (Phrase $phrase) {
                    $phrase->setRelation('savedBy', $phrase->users->random());

                    return $phrase;
                });

        return view('phrases.search', [
            'term' => $term,
            'phrases' => $phrases,
        ]);
    }
}
