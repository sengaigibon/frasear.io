<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Phrase;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class PhraseController extends Controller
{
    public function write(Request $request): View
    {
        return view('phrases.write');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'body' => 'required|string|max:250',
            'author' => 'nullable|string|max:100',
            'tags' => 'nullable|string|max:500',
        ]);

        $tagNames = collect(explode(',', $data['tags'] ?? ''))
            ->map(fn (string $tag): string => trim(strtolower($tag)))
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (!empty($tagNames)) {
            Validator::make(['tags' => $tagNames], [
                'tags' => [
                    function (string $attribute, mixed $value, \Closure $fail): void {
                        foreach ($value as $tagName) {
                            if (mb_strlen($tagName) > 50) {
                                $fail('Each tag must not be greater than 50 characters.');

                                return;
                            }
                        }
                    },
                ],
            ])->validate();
        }
        

        $authorName = trim($data['author'] ?? '');
        if ($authorName === '') {
            $author = Author::find(Author::ANONYMOUS_AUTHOR_ID);
        } else {
            // todo: ideally this should perform a similarity search; for later
            $author = Author::firstOrCreate(['name' => $authorName]);
        }

        // todo: perform similar text search for the phrase body:
        // @see requirements/notes/2026-09-09_iteration_2.md

        $phrase = Phrase::create([
            'body' => $data['body'],
            'author_id' => $author->id,
        ]);

        $tagIds = collect($tagNames)
            ->map(fn (string $tagName): int => Tag::firstOrCreate(['name' => $tagName])->id)
            ->all();

        $phrase->tags()->syncWithoutDetaching($tagIds);

        // Find current user and if no user fallback to id 1
        $user = auth()->user();
        if (!$user) {
            $user = User::find(1);
        }
        $user->phrases()->attach($phrase);
        $user->save();

        return redirect()->route('write')->with('status', 'Frase guardada.');
    }
}
