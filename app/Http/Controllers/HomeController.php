<?php

namespace App\Http\Controllers;

use App\Models\Phrase;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the home slider: 5 random phrases, each saved by at least one user.
     *
     * A fresh page load always pulls a new random 5 — there's no client-side
     * "load more" here by design, matching the "not reactive" requirement.
     */
    public function index(): View
    {
        $phrases = Phrase::query()
            ->whereHas('users')
            ->with(['author', 'users'])
            ->inRandomOrder()
            ->limit(5)
            ->get()
            ->map(function (Phrase $phrase) {
                // A phrase can be saved by multiple users; pick one at random
                // to credit as "saved by" for this display. Eloquent can't
                // limit a belongsToMany eager load per-parent-row, so we
                // load them all (small N) and pick here instead.
                $phrase->setRelation('savedBy', $phrase->users->random()); // todo: it might be useful to know the actual list of Users that saved a Phrase; for later.

                return $phrase;
            });

        return view('home', ['phrases' => $phrases]);
    }
}