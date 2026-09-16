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

        $author = Author::firstOrCreate(['name' => 'Anónimo']);
        $phrase1 = Phrase::firstOrCreate(['body' => "Mi primera frase", 'author_id' => $author->id]);
        $phrase2 = Phrase::firstOrCreate(['body' => "Mi segunda frase", 'author_id' => $author->id]);
        $phrase3 = Phrase::firstOrCreate(['body' => "Mi tercera frase", 'author_id' => $author->id]);

        $user->phrases()->attach($phrase1);
        $user->phrases()->attach($phrase2);
        $user->phrases()->attach($phrase3);
        
        $user->save();
    }
}