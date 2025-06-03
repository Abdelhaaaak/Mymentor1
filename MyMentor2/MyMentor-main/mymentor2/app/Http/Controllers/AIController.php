<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AIController extends Controller
{
    /**
     * Affiche votre page IA (ou redirige ailleurs).
     */
    public function find()
    {
        return view('ai.find');
    }
}
