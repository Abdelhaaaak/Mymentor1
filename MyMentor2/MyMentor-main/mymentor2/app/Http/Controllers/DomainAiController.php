<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DomainSuggestionService;

class DomainAiController extends Controller
{
    /**
     * Règle de validation "required|string" pour la plupart des champs texte.
     */
    protected const BASIC_RULE = 'required|string';

    private DomainSuggestionService $suggestion;

    public function __construct(DomainSuggestionService $suggestion)
    {
        $this->middleware('auth');
        $this->suggestion = $suggestion;
    }

    /**
     * Affiche le formulaire de suggestions de domaines.
     */
    public function form()
    {
        return view('domain.form');
    }

    /**
     * Traite le formulaire et affiche les résultats.
     */
    public function suggest(Request $request)
    {
        $data = $request->validate([
            'passions'       => self::BASIC_RULE,
            'taches_faciles' => self::BASIC_RULE,
            'flow_sujets'    => self::BASIC_RULE,
            'environnement'  => self::BASIC_RULE,
            'valeurs'        => self::BASIC_RULE,
            'strengths'      => self::BASIC_RULE,
            'objectifs_ct'   => self::BASIC_RULE,
            'objectifs_lt'   => self::BASIC_RULE,
            'learning'       => self::BASIC_RULE,
            'availability'   => 'required|integer|min:1'
        ]);

        $domains = $this->suggestion->suggest($data);

        // Mapping statique domaine → formations/institutions
        $map = [
            'Informatique' => [
                ['Université Mohammed V (Rabat)', 'Licence en Informatique'],
                ['ENSIAS (Rabat)',               'Master Ingénierie Logicielle'],
                ['EMI (Casablanca)',             'Génie Logiciel']
            ],
            'Droit'        => [
                ['Faculté de Droit (Mohammed V)',      'Licence Droit Privé'],
                ['École Supérieure de la Magistrature', 'Cycle de Magistrature'],
                ['Université Hassan II (Casablanca)',  'Master Droit des Affaires']
            ],
            'Gestion'      => [
                ['ENCG (Casablanca)', 'Licence Gestion'],
                ['ISCAE (Rabat)',     'Master Management'],
                ['EMI (Casablanca)',  'Management de Projets']
            ]
            // … ajouter d’autres clés si besoin …
        ];

        $recommendations = [];
        foreach ($domains as $domain) {
            if (isset($map[$domain])) {
                $recommendations[$domain] = $map[$domain];
            }
        }

        return view('domain.results', compact('domains', 'data', 'recommendations'));
    }
}
