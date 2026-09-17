<?php

use App\Models\Author;
use App\Models\User;

test('a phrase can be created with comma separated tags', function () {
    $user = User::factory()->create();
    Author::create(['name' => 'Anónimo']);

    $response = $this
        ->actingAs($user)
        ->post(route('write.store'), [
            'body' => 'A phrase worth keeping',
            'author' => 'A Test Author',
            'tags' => ' books, inspiration, books ',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('write'));

    $phrase = $user->phrases()->latest('phrases.id')->first();

    expect($phrase)->not->toBeNull();
    expect($phrase->tags()->pluck('name')->all())->toBe(['books', 'inspiration']);
    $this->assertDatabaseHas('tags', ['name' => 'books']);
    $this->assertDatabaseHas('tags', ['name' => 'inspiration']);
});

test('a tag cannot be longer than the database limit', function () {
    $user = User::factory()->create();
    Author::create(['name' => 'Anónimo']);

    $response = $this
        ->actingAs($user)
        ->from(route('write'))
        ->post(route('write.store'), [
            'body' => 'A phrase worth keeping',
            'author' => 'A Test Author',
            'tags' => str_repeat('a', 51),
        ]);

    $response
        ->assertSessionHasErrors('tags.0')
        ->assertRedirect(route('write'));

    $this->assertDatabaseMissing('tags', ['name' => str_repeat('a', 51)]);
});
