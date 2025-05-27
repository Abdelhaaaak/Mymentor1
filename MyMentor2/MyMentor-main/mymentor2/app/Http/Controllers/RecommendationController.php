<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Skill;

class RecommendationController extends Controller
{
    /**
     * Affiche le formulaire de recommandation.
     */
    public function showForm()
    {
        // Récupère toutes les compétences disponibles
        $skills = Skill::orderBy('name')->get();
        return view('recommend.form', compact('skills'));
    }

    /**
     * Traite le formulaire et affiche les résultats.
     */
    public function recommend(Request $request)
    {
        $request->validate([
            'goals'    => 'nullable|string',
            'skills'   => 'nullable|array',
            'language' => 'nullable|string',
            'level'    => 'nullable|string',
            'style'    => 'nullable|string',
        ]);

        $mentors = User::where('role', 'mentor')
                       ->with(['skills', 'feedbacks'])
                       ->get();

        $preferredSkills   = $request->skills   ?? [];
        $preferredLanguage = $request->language;
        $preferredLevel    = $request->level;
        $preferredStyle    = $request->style;
        $goalText          = strtolower($request->goals ?? '');
        $goalWords         = array_filter(explode(' ', $goalText));

        // Calcule un score pour chaque mentor
        $scored = $mentors->map(function ($m) use (
            $preferredSkills,
            $preferredLanguage,
            $preferredLevel,
            $preferredStyle,
            $goalWords
        ) {
            $score = 0;

            // Matching simple
            if ($m->language === $preferredLanguage) $score += 30;
            if ($m->level    === $preferredLevel)    $score += 10;
            if ($m->style    === $preferredStyle)    $score += 10;

            // Compétences communes
            $mskills      = $m->skills->pluck('name')->toArray();
            $common       = array_intersect($preferredSkills, $mskills);
            $score       += count($common) * 5;

            // Matching avancé sur objectifs vs compétences
            foreach ($goalWords as $word) {
                foreach ($mskills as $skill) {
                    similar_text(mb_strtolower($word), mb_strtolower($skill), $pct);
                    if ($pct >= 50) {
                        $score += $pct / 10;
                    }
                }
            }

            // Ajout du rating moyen
            $avg = $m->averageRating();
            if ($avg) $score += $avg * 3;

            $m->score = round($score, 2);
            return $m;
        });

        $sorted = $scored->sortByDesc('score')->values();

        return view('recommend.results', ['mentors' => $sorted]);
    }
}
