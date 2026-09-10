<?php

namespace App\Http\Controllers;

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
        // option 1: right now and present the result to the user
        // option 2: during a batch night process, this would be handy
        //           as users can be notified and they can start a "conflict resolution" conversation
        //           which in turn could be potentially beneficial for "hydrating" relationships between
        //           people with similar interests
        // both options are complex

        $phrase = Phrase::create([
            'body' => $data['body'],
            'author_id' => $author->id,
        ]);



        return redirect()->route('write')->with('status', 'Phrase saved.');
    }
}