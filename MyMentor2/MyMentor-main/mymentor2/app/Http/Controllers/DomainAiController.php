<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DomainSuggestionService;

class DomainAiController extends Controller
{
    private DomainSuggestionService $suggestion;

    public function __construct(DomainSuggestionService $suggestion)
    {
        $this->middleware('auth');
        $this->suggestion = $suggestion;
    }

    /** Affiche le formulaire */
    public function form()
    {
        return view('domain.form');
    }

    /** Traite la soumission et affiche les résultats */
    public function suggest(Request $request)
    {
        $data = $request->validate([
            'passions'      => 'required|string',
            'taches_faciles'=> 'required|string',
            'flow_sujets'   => 'required|string',
            'environnement' => 'required|string',
            'valeurs'       => 'required|string',
            'strengths'     => 'required|string',
            'objectifs_ct'  => 'required|string',
            'objectifs_lt'  => 'required|string',
            'learning'      => 'required|string',
            'availability'  => 'required|integer|min:1',
        ]);

        $domains = $this->suggestion->suggest($data);

        // Mapping statique domaines → établissements
        $map = [
            'Informatique' => [
                ['Université Mohammed V (Rabat)', 'Licence en Informatique'],
                ['ENSIAS (Rabat)',               'Master Ingénierie Logicielle'],
                ['EMI (Casablanca)',             'Génie Logiciel']
            ],
            'Droit'        => [
                ['Faculté de Droit (Mohammed V)',     'Licence Droit Privé'],
                ['École Supérieure de la Magistrature','Cycle de Magistrature'],
                ['Université Hassan II (Casablanca)', 'Master Droit des Affaires']
            ],
            'Gestion'      => [
                ['ENCG (Casablanca)', 'Licence Gestion'],
                ['ISCAE (Rabat)',     'Master Management'],
                ['EMI (Casablanca)',  'Management de Projets']
            ],
            // … ajoutez autant de clés que nécessaire …
        ];

        // Construire les recommandations pour les domaines retournés
        $recommendations = [];
        foreach ($domains as $domain) {
            if (isset($map[$domain])) {
                $recommendations[$domain] = $map[$domain];
            }
        }

        return view('domain.results', compact('domains', 'data', 'recommendations'));
    }
}
