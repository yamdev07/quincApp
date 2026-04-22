<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GroqAIService
{
    private string $apiKey;
    private string $model;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey  = config('services.groq.api_key');
        $this->model   = config('services.groq.model');
        $this->baseUrl = config('services.groq.base_url');
    }

    public function analyze(string $systemPrompt, string $userMessage): string
    {
        if (empty($this->apiKey) || $this->apiKey === 'your_groq_api_key_here') {
            return "⚠️ Clé API Groq non configurée. Ajoutez votre clé dans le fichier .env (GROQ_API_KEY).";
        }

        $response = Http::withToken($this->apiKey)
            ->timeout(60)
            ->post("{$this->baseUrl}/chat/completions", [
                'model'       => $this->model,
                'messages'    => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user',   'content' => $userMessage],
                ],
                'temperature' => 0.4,
                'max_tokens'  => 1500,
            ]);

        if ($response->failed()) {
            $error = $response->json('error.message') ?? 'Erreur inconnue';
            return "❌ Erreur API Groq : {$error}";
        }

        return $response->json('choices.0.message.content') ?? 'Aucune réponse reçue.';
    }
}
