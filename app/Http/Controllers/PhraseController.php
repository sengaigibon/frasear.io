<?php

namespace App\Http\Controllers;

use App\Models\Phrase;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PhraseSearchController extends Controller
{
    /**
     * NOTE: this is a functional placeholder, not the designed search
     * results page — that page is still an open question in the
     * requirements doc ("I have to think about it"). This exists so the
     * nav search field in the home design has somewhere real to go;
     * revisit the layout/design once the search page itself is scoped.
     */
    public function index(Request $request): View
    {
        $term = trim((string) $request->query('tag', ''));

        $phrases = $term === ''
            ? collect()
            : Phrase::query()
                ->whereHas('tags', fn ($q) => $q->where('name', 'ilike', "%{$term}%"))
                ->with(['author', 'tags'])
                ->limit(20)
                ->get();

        return view('phrases.search', [
            'term' => $term,
            'phrases' => $phrases,
        ]);
    }
}