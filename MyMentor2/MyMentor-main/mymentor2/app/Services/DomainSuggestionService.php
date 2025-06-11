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
     * @throws CohereConfigurationException
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
     * @param  array<string,mixed>  $answers
     * @return array<int,string>  Suggestions en français
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function suggest(array $answers): array
    {
        // Construction du prompt avec instruction linguistique claire
        $prompt = sprintf(
            "Tu es un conseiller d’orientation virtuel.\n" .
            "À partir des informations suivantes, propose une liste de 5 secteurs professionnels pertinents.\n" .
            "Les réponses doivent être en français clair et professionnel, sous forme de liste avec un tiret par ligne.\n" .
            "- Passions : %s\n" .
            "- Tâches faciles : %s\n" .
            "- Sujets en état de flux : %s\n" .
            "- Environnement préféré : %s\n" .
            "- Valeurs personnelles : %s\n" .
            "- Compétences clés : %s\n" .
            "- Objectifs à court terme : %s\n" .
            "- Objectifs à long terme : %s\n" .
            "- Sujets à apprendre : %s\n" .
            "- Temps disponible par semaine : %s heures\n\n" .
            "Réponds uniquement avec une liste de 5 secteurs professionnels adaptés, en français, sans introduction ni conclusion.",
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

        // Requête POST à l'API Cohere
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type'  => 'application/json',
        ])->post($this->apiUrl, [
            'model'       => $this->model,
            'messages'    => [
                ['role' => 'user', 'content' => $prompt]
            ],
            'max_tokens'  => 300,
            'temperature' => 0.7,
        ]);

        $response->throw();

        // Extraction du texte brut de la réponse
        $text = $response->json('message.content.0.text', '');

        // Nettoyage et conversion en tableau
        return collect(explode("\n", $text))
            ->filter(fn (string $line) => trim($line) !== '')
            ->map(fn (string $line) => trim($line, "- \t\n\r\0\x0B"))
            ->values()
            ->toArray();
    }
}
