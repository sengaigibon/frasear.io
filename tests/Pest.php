<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}

/**
 * Create a phrase, attach it to the given tag name, and mark it as saved
 * by $user. Mirrors how PhraseController::store builds real data, without
 * going through that route. Shared across Feature tests that need a
 * quick, realistic phrase+tag+saver setup (search, and later the phrase
 * management list).
 */
function createSearchablePhrase(\App\Models\User $user, string $body, string $tagName): \App\Models\Phrase
{
    $author = \App\Models\Author::firstOrCreate(['name' => 'Anónimo']);

    $phrase = \App\Models\Phrase::create([
        'body' => $body,
        'author_id' => $author->id,
    ]);

    $tag = \App\Models\Tag::firstOrCreate(['name' => $tagName]);
    $phrase->tags()->attach($tag->id);

    $user->phrases()->attach($phrase->id);

    return $phrase;
}
