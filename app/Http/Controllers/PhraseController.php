<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Phrase;
use App\Models\Author;
use Illuminate\Http\RedirectResponse;

class PhraseController extends Controller
{
    public function write(Request $request): View
    {
        return view('phrases.write');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'body' => 'required|string|max:1000',
            'author' => 'nullable|string|max:255',
        ]);

        $authorName = trim($data['author'] ?? '');
        if ($authorName === '') {
            $author = Author::find(Author::ANONYMOUS_AUTHOR_ID);
        } else {
            // todo: ideally this should perform a similarity search; for later
            $author = Author::firstOrCreate(['name' => $authorName]);
        }

        //todo: perform similar text search for the phrase body:
        // @see requirements/notes/2026-09-09_iteration_2.md

        $phrase = Phrase::create([
            'body' => $data['body'],
            'author_id' => $author->id,
        ]);

        // Find current user and if no user fallback to id 1
        $user = auth()->user();
        if (!$user) {
            $user = User::find(1);
        }
        $user->phrases()->attach($phrase);
        $user->save();



        return redirect()->route('write')->with('status', 'Phrase saved.');
    }
}