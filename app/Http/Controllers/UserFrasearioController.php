<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Phrase;
use Illuminate\View\View;

class UserFrasearioController extends Controller
{
    public function show(string $username): View
    {
        $user = User::where('username', $username)->firstOrFail();

        $phrases = $user->phrases()
            ->with(['author'])
            ->inRandomOrder()
            ->limit(5)
            ->get()
            ->map(function (Phrase $phrase) use ($user) {
                // For the personal fraseario, the phrase is credited to this user
                $phrase->setRelation('savedBy', $user);
                return $phrase;
            });

        return view('user_fraseario.show', [
            'user' => $user,
            'phrases' => $phrases,
        ]);
    }
}