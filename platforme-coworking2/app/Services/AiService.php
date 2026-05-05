<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiService
{
    private string $apiKey;
    private string $model;

    public function __construct()
    {
        $this->apiKey = (string) config('services.groq.key');
        $this->model  = (string) config('services.groq.model', 'llama-3.3-70b-versatile');
    }

    public function analyserSentiment(string $texte): array
    {
        if (empty($this->apiKey)) {
            return ['sentiment' => 'neutre', 'score' => 0.5];
        }

        $prompt = "Analyse ce commentaire client en français et réponds UNIQUEMENT avec un JSON valide "
                . "au format {\"sentiment\":\"positif|neutre|negatif\",\"score\":0.0}. "
                . "Le score est ta confiance entre 0 et 1. Avis : \"{$texte}\"";

        try {
            $response = Http::withOptions(['verify' => false])
                ->withToken($this->apiKey)
                ->timeout(20)
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => $this->model,
                    'messages' => [['role' => 'user', 'content' => $prompt]],
                    'temperature' => 0.1,
                    'max_tokens' => 80,
                    'response_format' => ['type' => 'json_object'],
                ]);

            $contenu = $response->json('choices.0.message.content', '');
            $data = json_decode($contenu, true);

            if (!is_array($data) || !isset($data['sentiment'])) {
                return ['sentiment' => 'neutre', 'score' => 0.5];
            }

            return [
                'sentiment' => in_array($data['sentiment'], ['positif','neutre','negatif']) ? $data['sentiment'] : 'neutre',
                'score'     => (float) ($data['score'] ?? 0.5),
            ];
        } catch (\Throwable $e) {
            Log::error('Erreur Groq sentiment : ' . $e->getMessage());
            return ['sentiment' => 'neutre', 'score' => 0.5];
        }
    }
}