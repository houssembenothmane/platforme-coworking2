<?php

namespace App\Http\Controllers;

use App\Models\Espace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function repondre(Request $request)
    {
        $request->validate(['question' => 'required|string|max:500']);

        $apiKey = config('services.groq.key');
        $model  = config('services.groq.model');

        if (empty($apiKey)) {
            return response()->json(['reponse' => "⚠️ Clé Groq non configurée."]);
        }

        $espaces = Espace::select('nom', 'description', 'prix_heure', 'capacite', 'statut')
            ->where('statut', 'Disponible')
            ->take(20)
            ->get()
            ->toArray();

        $systemPrompt = "Tu es l'assistant virtuel de CoWork Tunisie, plateforme de réservation d'espaces de coworking. "
                        . "Réponds en français, court (max 3 phrases), clair et amical. "
                        . "Voici les espaces disponibles : " . json_encode($espaces, JSON_UNESCAPED_UNICODE) . ". "
                        . "Pour réserver, redirige vers /espaces.";

        try {
            $response = Http::withOptions(['verify' => false])
                ->withToken($apiKey)
                ->timeout(30)
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user',   'content' => $request->question],
                    ],
                    'temperature' => 0.3,
                    'max_tokens' => 256,
                ]);

            if (!$response->successful()) {
                $err = $response->json('error.message', $response->body());
                return response()->json(['reponse' => "⚠️ Groq HTTP {$response->status()} : {$err}"]);
            }

            $texte = $response->json('choices.0.message.content', '');
            return response()->json(['reponse' => $texte ?: "Désolé, je n'ai pas pu répondre."]);

        } catch (\Throwable $e) {
            return response()->json(['reponse' => "⚠️ Exception : " . $e->getMessage()]);
        }
    }
}