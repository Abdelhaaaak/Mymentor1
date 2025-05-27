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
        // TODO : remplacer par votre logique
        return view('ai.find');
    }
}
