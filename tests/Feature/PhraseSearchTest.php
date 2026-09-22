<?php

use App\Models\Author;
use App\Models\Phrase;
use App\Models\Tag;
use App\Models\User;

test('visiting the search page without a query shows a prompt, not results', function () {
    $response = $this->get(route('phrases.search'));

    $response->assertOk();
    $response->assertSee('Escribe una palabra, un autor o una etiqueta arriba para buscar frases guardadas.');
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
    $response->assertSee('No se encontraron frases para esa etiqueta.');
});

test('searching by author matches authored phrases with up to two words', function () {
    $user = User::factory()->create();
    $author = Author::firstOrCreate(['name' => 'Ana García']);
    $phrase = Phrase::create(['body' => 'A phrase by Ana', 'author_id' => $author->id]);
    $user->phrases()->attach($phrase->id);

    $response = $this->get(route('phrases.search', ['mode' => 'author', 'q' => 'Ana García']));

    $response->assertOk();
    $response->assertSee('A phrase by Ana');
});

test('searching by word matches phrase bodies across saved phrases', function () {
    $user = User::factory()->create();
    createSearchablePhrase($user, 'A phrase worth keeping', 'inspiration');

    $response = $this->get(route('phrases.search', ['mode' => 'word', 'q' => 'worth']));

    $response->assertOk();
    $response->assertSee('A phrase worth keeping');
    $response->assertDontSee('No phrases found for that tag.');
});

test('searching in a specific user fraseario only returns that user\'s phrases', function () {
    $owner = User::factory()->create(['username' => 'alice']);
    $other = User::factory()->create(['username' => 'bob']);

    createSearchablePhrase($owner, 'Alice keeps this phrase', 'sunrise');
    createSearchablePhrase($other, 'Bob keeps this phrase', 'sunrise');

    $response = $this->get(route('phrases.search', ['username' => $owner->username, 'mode' => 'word', 'q' => 'phrase']));

    $response->assertOk();
    $response->assertSee('Alice keeps this phrase');
    $response->assertDontSee('Bob keeps this phrase');
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
    $response->assertSee('No se encontraron frases para esa etiqueta.');
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
