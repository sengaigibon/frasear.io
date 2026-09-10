<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class PhraseController extends Controller
{
    public function write(Request $request): View
    {
        return view('phrases.write');
    }
}