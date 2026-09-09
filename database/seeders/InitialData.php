<?php

namespace Database\Seeders;

use \App\Models\Author;
use \App\Models\Phrase;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InitialData extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['username' => 'jknight'],
            [
                'name' => 'Javier Caballero',
                'email' => 'email@email.com',
                'password' => Hash::make('password'),
                'profile' => 'Escalador, montañista, naturalista;',
            ]
        );

        $author = Author::firstOrCreate(['name' => 'Anonymous']);
        $phrase = Phrase::firstOrCreate(['body' => "Mi primera frase", 'author_id' => $author->id]);

        $user->phrases()->attach($phrase);
        $user->save();
    }
}