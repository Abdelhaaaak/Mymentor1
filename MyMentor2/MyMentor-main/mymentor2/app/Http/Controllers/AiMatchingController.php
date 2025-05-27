<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AiMatchingController extends Controller
{
    // Show AI matching page (for mentor recommendations)
    public function index()
    {
        // Get mentoré's skills and find mentors based on matching skills (AI logic)
        $userSkills = auth()->user()->skills()->pluck('id');  // Get mentoré's skills
        $recommendedMentors = User::whereHas('skills', function($query) use ($userSkills) {
            $query->whereIn('id', $userSkills);  // Match mentors with similar skills
        })->get();

        return view('ai.matching', compact('recommendedMentors'));
    }
}
