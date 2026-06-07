<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AIAssistantController extends Controller
{
    public function index()
    {
        return view('admin.ai.index');
    }

    public function hospitalIndex()
    {
        return view('hospital.ai.index');
    }

    public function chat(Request $request)
    {
        $request->validate(['message' => 'required|string|max:2000']);

        $messages = [
            [
                'role'    => 'system',
                'content' => 'Tu es MedBot, un assistant IA expert en gestion de fournitures médicales pour la plateforme MedSupply. Tu aides les administrateurs et les chefs d\'hôpitaux à gérer les commandes, les stocks, les fournisseurs et les hôpitaux. Réponds toujours en français, de manière concise et professionnelle.'
            ]
        ];

        foreach ($request->history ?? [] as $h) {
            if (in_array($h['role'] ?? '', ['user', 'assistant'])) {
                $messages[] = [
                    'role'    => $h['role'],
                    'content' => (string) $h['content'],
                ];
            }
        }

        $messages[] = [
            'role'    => 'user',
            'content' => (string) $request->message,
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
                'Content-Type'  => 'application/json',
            ])->timeout(30)->post('https://api.groq.com/openai/v1/chat/completions', [
                'model'      => 'llama-3.3-70b-versatile',
                'messages'   => $messages,
                'max_tokens' => 1024,
            ]);

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'reply'   => 'Erreur API : ' . $response->status() . ' — ' . $response->body(),
                ]);
            }

            $reply = $response->json('choices.0.message.content');

            if (!$reply) {
                return response()->json([
                    'success' => false,
                    'reply'   => 'Réponse vide du service IA.',
                ]);
            }

            return response()->json(['success' => true, 'reply' => $reply]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'reply'   => 'Erreur : ' . $e->getMessage(),
            ]);
        }
    }
}