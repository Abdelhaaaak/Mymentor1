<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DomainSuggestionService
{
    private string $apiKey;
    private string $apiUrl;
    private string $model;

    public function __construct()
    {
        $this->apiKey = config('services.cohere.token');
        $this->apiUrl = config('services.cohere.endpoint');
        $this->model  = config('services.cohere.model');

        if (empty($this->apiKey) || empty($this->apiUrl) || empty($this->model)) {
            throw new \RuntimeException('Configuration Cohere incomplète');
        }
    }

    /**
     * Suggère des secteurs via Cohere Chat API v2
     *
     * @param array $answers
     * @return array
     */
    public function suggest(array $answers): array
    {
        // Construction du prompt avec toutes les réponses
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
            $answers['passions'],
            $answers['taches_faciles'],
            $answers['flow_sujets'],
            $answers['environnement'],
            $answers['valeurs'],
            $answers['strengths'],
            $answers['objectifs_ct'],
            $answers['objectifs_lt'],
            $answers['learning'],
            $answers['availability']
        );

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type'  => 'application/json',
        ])->post($this->apiUrl, [
            'model'    => $this->model,
            'messages' => [ ['role' => 'user', 'content' => $prompt] ],
            'max_tokens'  => 300,
            'temperature' => 0.7,
        ]);

        $response->throw();

        $text = $response->json('message.content.0.text', '');
        return collect(explode("\n", $text))
            ->filter(fn($line) => trim($line) !== '')
            ->map(fn($line) => trim($line, "- "))
            ->values()
            ->toArray();
    }
}