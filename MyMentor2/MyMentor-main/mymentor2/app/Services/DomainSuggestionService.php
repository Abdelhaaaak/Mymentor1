<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Exceptions\CohereConfigurationException;

class DomainSuggestionService
{
    private string $apiKey;
    private string $apiUrl;
    private string $model;

    /**
     * DomainSuggestionService constructor.
     *
     * @throws CohereConfigurationException  Si la configuration (clé, point d’entrée ou modèle) est manquante.
     */
    public function __construct()
    {
        $this->apiKey = config('services.cohere.token');
        $this->apiUrl = config('services.cohere.endpoint');
        $this->model  = config('services.cohere.model');

        if (empty($this->apiKey) || empty($this->apiUrl) || empty($this->model)) {
            throw new CohereConfigurationException(
                'Configuration Cohere incomplète : vérifiez les clés "services.cohere.token", ' .
                '"services.cohere.endpoint" et "services.cohere.model" dans config/services.php.'
            );
        }
    }

    /**
     * Suggère des secteurs d’activité via l’API Cohere Chat v2.
     *
     * @param  array<string,mixed>  $answers  Les réponses de l’utilisateur :
     *     [
     *       'passions'       => string,
     *       'taches_faciles' => string,
     *       'flow_sujets'    => string,
     *       'environnement'  => string,
     *       'valeurs'        => string,
     *       'strengths'      => string,
     *       'objectifs_ct'   => string,
     *       'objectifs_lt'   => string,
     *       'learning'       => string,
     *       'availability'   => string,
     *     ]
     *
     * @return array<int,string>  Tableau contenant 5 suggestions de secteurs.
     *
     * @throws \Illuminate\Http\Client\RequestException  Si l’appel HTTP échoue (codes 4xx/5xx).
     */
    public function suggest(array $answers): array
    {
        // 1) Construction du prompt formaté
        $prompt = sprintf(
            "En tant que coach de carrière virtuel, propose 5 secteurs d'activité adaptés à une personne avec :\n" .
            "- Passions : %s\n" .
            "- Tâches faciles : %s\n" .
            "- Sujets en état de flux : %s\n" .
            "- Environnement idéal : %s\n" .
            "- Valeurs personnelles : %s\n" .
            "- Compétences maîtrisées : %s\n" .
            "- Objectifs court terme : %s\n" .
            "- Objectifs long terme : %s\n" .
            "- Envie d'apprendre : %s\n" .
            "- Disponibilité (h/semaine) : %s\n",
            $answers['passions']       ?? '',
            $answers['taches_faciles'] ?? '',
            $answers['flow_sujets']    ?? '',
            $answers['environnement']  ?? '',
            $answers['valeurs']        ?? '',
            $answers['strengths']      ?? '',
            $answers['objectifs_ct']   ?? '',
            $answers['objectifs_lt']   ?? '',
            $answers['learning']       ?? '',
            $answers['availability']   ?? ''
        );

        // 2) Envoi de la requête HTTP POST à l’API Cohere
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type'  => 'application/json'
        ])->post($this->apiUrl, [
            'model'       => $this->model,
            'messages'    => [
                [ 'role' => 'user', 'content' => $prompt ]
            ],
            'max_tokens'  => 300,
            'temperature' => 0.7,
        ]);

        // Lance une exception si le code HTTP renvoyé est 4xx ou 5xx
        $response->throw();

        // 3) Récupération du texte brut dans la réponse JSON
        $text = $response->json('message.content.0.text', '');

        // 4) Découpage des lignes, suppression des lignes vides, suppression de tout symbole “- ”,
        //    puis transformation en tableau indexé (0..4).
        return collect(explode("\n", $text))
            ->filter(fn (string $line) => trim($line) !== '')
            ->map(fn (string $line) => trim($line, "- "))
            ->values()
            ->toArray();
    }
}
