<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Skill;

class RecommendationController extends Controller
{
    /**
     * Règle de validation pour un champ textuel nullable.
     */
    protected const NULLABLE_STRING = 'nullable|string';

    /**
     * Affiche le formulaire de recommandation (sélection de compétences, objectifs…).
     *
     * @return \Illuminate\View\View
     */
    public function showForm()
    {
        $skills = Skill::orderBy('name')->get();
        return view('recommend.form', compact('skills'));
    }

    /**
     * Traite le formulaire et calcule un score pour chaque mentor.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function recommend(Request $request)
    {
        // 1) Validation des champs d’entrée
        $request->validate([
            'goals'    => self::NULLABLE_STRING,
            'skills'   => 'nullable|array',
            'language' => self::NULLABLE_STRING,
            'level'    => self::NULLABLE_STRING,
            'style'    => self::NULLABLE_STRING
        ]);

        // 2) Récupérer tous les mentors avec leurs compétences et feedbacks
        $mentors = User::where('role', 'mentor')
                       ->with(['skills', 'feedbacks'])
                       ->get();

        // 3) Extraire les préférences de l’utilisateur
        $preferredSkills   = $request->skills   ?? [];
        $preferredLanguage = $request->language;
        $preferredLevel    = $request->level;
        $preferredStyle    = $request->style;
        $goalWords         = $this->extractGoalWords($request->goals);

        // 4) Calculer le score pour chaque mentor en déléguant la logique à des méthodes privées
        $scored = $mentors->map(function (User $mentor) use (
            $preferredSkills,
            $preferredLanguage,
            $preferredLevel,
            $preferredStyle,
            $goalWords
        ) {
            // 4A) Score pour correspondance exacte (langue, niveau, style)
            $score = $this->computeExactMatchScore(
                $mentor,
                $preferredLanguage,
                $preferredLevel,
                $preferredStyle
            );

            // 4B) Score pour compétences communes
            $mentorSkills = $mentor->skills->pluck('name')->toArray();
            $score       += $this->computeCommonSkillsScore(
                $mentorSkills,
                $preferredSkills
            );

            // 4C) Score “flou” entre mots d’objectifs et compétences du mentor
            $score       += $this->computeFuzzyMatchScore(
                $mentorSkills,
                $goalWords
            );

            // 4D) Score basé sur la note moyenne (feedback)
            $score       += $this->computeRatingScore(
                $mentor->averageRating()
            );

            $mentor->score = round($score, 2);

            return $mentor;
        });

        // 5) Trier par score décroissant et retourner la vue
        $sorted = $scored->sortByDesc('score')->values();
        return view('recommend.results', ['mentors' => $sorted]);
    }

    /**
     * Extrait les mots‐clés des objectifs.
     *
     * @param  string|null  $goals
     * @return array<int,string>
     */
    private function extractGoalWords(?string $goals): array
    {
        $text = strtolower($goals ?? '');
        return array_filter(explode(' ', $text));
    }

    /**
     * Calcule le score pour la correspondance exacte sur la langue, le niveau et le style.
     *
     * @param  \App\Models\User  $mentor
     * @param  string|null       $language
     * @param  string|null       $level
     * @param  string|null       $style
     * @return int
     */
    private function computeExactMatchScore(
        User $mentor,
        ?string $language,
        ?string $level,
        ?string $style
    ): int {
        $score = 0;

        if ($mentor->language === $language) {
            $score += 30;
        }

        if ($mentor->level === $level) {
            $score += 10;
        }

        if ($mentor->style === $style) {
            $score += 10;
        }

        return $score;
    }

    /**
     * Calcule le score basé sur le nombre de compétences communes.
     *
     * @param  array<int,string>  $mentorSkills
     * @param  array<int,string>  $preferredSkills
     * @return int
     */
    private function computeCommonSkillsScore(array $mentorSkills, array $preferredSkills): int
    {
        $common = array_intersect($preferredSkills, $mentorSkills);
        return count($common) * 5;
    }

    /**
     * Calcule le score par correspondance “floue” entre les mots d’objectifs et les compétences du mentor.
     * Pour chaque mot d’objectif, compare à chaque compétence avec similar_text. Si similarité >= 50%, ajoute (percent / 10).
     *
     * @param  array<int,string>  $mentorSkills
     * @param  array<int,string>  $goalWords
     * @return float
     */
    private function computeFuzzyMatchScore(array $mentorSkills, array $goalWords): float
    {
        $score = 0.0;

        foreach ($goalWords as $word) {
            $wordLower = mb_strtolower($word);

            foreach ($mentorSkills as $skill) {
                similar_text($wordLower, mb_strtolower($skill), $percent);

                if ($percent >= 50) {
                    $score += $percent / 10.0;
                }
            }
        }

        return $score;
    }

    /**
     * Calcule le score basé sur la note moyenne (feedback), multipliée par 3.
     *
     * @param  float|null  $avgRating
     * @return float
     */
    private function computeRatingScore(?float $avgRating): float
    {
        return $avgRating ? $avgRating * 3 : 0;
    }
}
