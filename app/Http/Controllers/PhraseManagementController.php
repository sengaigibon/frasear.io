<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Phrase;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class PhraseManagementController extends Controller
{
    public function index(Request $request): View
    {
        $phrases = $request->user()
            ->phrases()
            ->with(['author', 'tags'])
            ->orderByDesc('users_phrases.created_at')
            ->paginate(15);

        return view('phrases.manage', [
            'phrases' => $phrases,
        ]);
    }

    public function edit(Request $request, Phrase $phrase): View
    {
        $phrase = $request->user()->phrases()->with('tags')->findOrFail($phrase->id);

        return view('phrases.edit', [
            'phrase' => $phrase,
        ]);
    }

    public function update(Request $request, Phrase $phrase): RedirectResponse
    {
        $phrase = $request->user()->phrases()->findOrFail($phrase->id);

        $data = $request->validate([
            'body' => ['required', 'string', 'max:250'],
            'author' => ['nullable', 'string', 'max:100'],
            'tags' => ['nullable', 'string', 'max:500'],
        ]);

        $tagNames = collect(explode(',', $data['tags'] ?? ''))
            ->map(fn (string $tag): string => trim(strtolower($tag)))
            ->filter()
            ->unique()
            ->values()
            ->all();

        Validator::make(['tags' => $tagNames], [
            'tags' => ['array'],
            'tags.*' => ['string', 'max:50'],
        ])->validate();

        $authorName = trim($data['author'] ?? '');
        $author = Author::firstOrCreate([
            'name' => $authorName === '' ? 'Anónimo' : $authorName,
        ]);

        $phrase->update([
            'body' => $data['body'],
            'author_id' => $author->id,
        ]);

        $tagIds = collect($tagNames)
            ->map(fn (string $tagName): int => Tag::firstOrCreate(['name' => $tagName])->id)
            ->all();

        $phrase->tags()->sync($tagIds);

        return redirect()->route('phrases.manage')->with('status', __('Phrase updated successfully.'));
    }

    public function destroy(Request $request, Phrase $phrase): RedirectResponse
    {
        $phrase = $request->user()->phrases()->findOrFail($phrase->id);

        DB::transaction(function () use ($request, $phrase): void {
            $phrase = Phrase::query()
                ->whereKey($phrase->id)
                ->lockForUpdate()
                ->firstOrFail();

            $request->user()->phrases()->detach($phrase->id);

            if (! $phrase->users()->exists()) {
                $phrase->delete();
            }
        });

        return redirect()->route('phrases.manage')->with('status', __('Phrase removed from your fraseario.'));
    }
}
