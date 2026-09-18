<?php

use App\Models\Author;
use App\Models\Phrase;
use App\Models\Tag;
use App\Models\User;

test('guests cannot access phrase management', function () {
    $this->get(route('phrases.manage'))
        ->assertRedirect(route('login'));
});

test('a user sees only their attached phrases in a paginated list', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $author = Author::create(['name' => 'An Author']);

    $ownedPhrases = collect(range(1, 16))->map(function (int $number) use ($author) {
        return Phrase::create([
            'body' => "Owned phrase {$number}",
            'author_id' => $author->id,
        ]);
    });

    $otherPhrase = Phrase::create([
        'body' => 'Another user phrase',
        'author_id' => $author->id,
    ]);

    $ownedPhrases->each(function (Phrase $phrase, int $index) use ($user): void {
        $user->phrases()->attach($phrase, [
            'created_at' => now()->addSeconds($index),
        ]);
    });
    $otherUser->phrases()->attach($otherPhrase);

    $this->actingAs($user)
        ->get(route('phrases.manage'))
        ->assertOk()
        ->assertSee('Owned phrase 16')
        ->assertDontSee('Another user phrase')
        ->assertSee('Page 1 of 2');

    $this->actingAs($user)
        ->get(route('phrases.manage', ['page' => 2]))
        ->assertOk()
        ->assertSee('Owned phrase 1')
        ->assertDontSee('Owned phrase 16');
});

test('a user can edit an attached phrase and its tags', function () {
    $user = User::factory()->create();
    $phrase = Phrase::create([
        'body' => 'Original phrase',
        'author_id' => Author::create(['name' => 'Original Author'])->id,
    ]);
    $user->phrases()->attach($phrase);
    $phrase->tags()->attach(Tag::create(['name' => 'old-tag']));

    $this->actingAs($user)
        ->patch(route('phrases.update', $phrase), [
            'body' => 'Updated phrase',
            'author' => 'Updated Author',
            'tags' => 'new-tag, another-tag',
        ])
        ->assertRedirect(route('phrases.manage'));

    expect($phrase->refresh()->body)->toBe('Updated phrase');
    expect($phrase->author->name)->toBe('Updated Author');
    expect($phrase->tags()->pluck('name')->all())->toBe(['new-tag', 'another-tag']);
});

test('removing a phrase detaches it only from the current user', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $phrase = Phrase::create([
        'body' => 'Shared phrase',
        'author_id' => Author::create(['name' => 'An Author'])->id,
    ]);
    $user->phrases()->attach($phrase);
    $otherUser->phrases()->attach($phrase);

    $this->actingAs($user)
        ->delete(route('phrases.destroy', $phrase))
        ->assertRedirect(route('phrases.manage'));

    $this->assertDatabaseMissing('users_phrases', [
        'user_id' => $user->id,
        'phrase_id' => $phrase->id,
    ]);
    $this->assertDatabaseHas('users_phrases', [
        'user_id' => $otherUser->id,
        'phrase_id' => $phrase->id,
    ]);
    $this->assertDatabaseHas('phrases', ['id' => $phrase->id]);
});

test('removing the last attachment permanently deletes the phrase', function () {
    $user = User::factory()->create();
    $phrase = Phrase::create([
        'body' => 'Only attached phrase',
        'author_id' => Author::create(['name' => 'An Author'])->id,
    ]);
    $user->phrases()->attach($phrase);

    $this->actingAs($user)
        ->delete(route('phrases.destroy', $phrase))
        ->assertRedirect(route('phrases.manage'));

    $this->assertDatabaseMissing('users_phrases', [
        'user_id' => $user->id,
        'phrase_id' => $phrase->id,
    ]);
    $this->assertDatabaseMissing('phrases', ['id' => $phrase->id]);
});
