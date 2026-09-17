<?php

use App\Models\Author;
use App\Models\Phrase;
use App\Models\Tag;
use App\Models\User;

test('visiting the search page without a tag shows a prompt, not results', function () {
    $response = $this->get(route('phrases.search'));

    $response->assertOk();
    $response->assertSee('Type a tag above to search saved phrases.');
});

test('searching an existing tag returns the matching phrase', function () {
    $user = User::factory()->create();
    createSearchablePhrase($user, 'A phrase worth keeping', 'inspiration');

    $response = $this->get(route('phrases.search', ['tag' => 'inspiration']));

    $response->assertOk();
    $response->assertSee('A phrase worth keeping');
    $response->assertSee('#inspiration');
});

test('tag search is case-insensitive and matches partial tag names', function () {
    $user = User::factory()->create();
    createSearchablePhrase($user, 'Climb every mountain', 'Mountaineering');

    $response = $this->get(route('phrases.search', ['tag' => 'mountain']));

    $response->assertOk();
    $response->assertSee('Climb every mountain');
});

test('searching a tag with no matches shows an empty state', function () {
    $response = $this->get(route('phrases.search', ['tag' => 'doesnotexist']));

    $response->assertOk();
    $response->assertSee('No phrases found for that tag.');
});

test('a phrase with a matching tag but no saver is excluded from results', function () {
    $author = Author::firstOrCreate(['name' => 'Anónimo']);
    $phrase = Phrase::create(['body' => 'Nobody saved this one', 'author_id' => $author->id]);
    $tag = Tag::firstOrCreate(['name' => 'orphaned']);
    $phrase->tags()->attach($tag->id);
    // Deliberately no $user->phrases()->attach() call here — this is the
    // exact case the whereHas('users') clause in the controller exists for.

    $response = $this->get(route('phrases.search', ['tag' => 'orphaned']));

    $response->assertOk();
    $response->assertDontSee('Nobody saved this one');
    $response->assertSee('No phrases found for that tag.');
});

test('search results paginate and the tag stays in the query string across pages', function () {
    $user = User::factory()->create();

    foreach (range(1, 20) as $i) {
        createSearchablePhrase($user, "Phrase number {$i}", 'paginated');
    }

    $firstPage = $this->get(route('phrases.search', ['tag' => 'paginated']));
    $firstPage->assertOk();
    $firstPage->assertSee('Page 1 of 2');

    $secondPage = $this->get(route('phrases.search', ['tag' => 'paginated', 'page' => 2]));
    $secondPage->assertOk();
    $secondPage->assertSee('Page 2 of 2');
});
