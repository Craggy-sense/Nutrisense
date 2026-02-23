<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AIController extends Controller
{
    /**
     * CHAT: Proxies the user's question to the Groq AI service.
     * This acts as a middleman to keep your API key secure.
     */
    public function chat(Request $request)
    {
        // 1. Basic validation of the message
        $validated = $request->validate([
            'message' => 'required|string',
            'context' => 'nullable|string',
        ]);

        // 2. Pull the secret API key from the .env file (NEVER hardcode keys!)
        $apiKey = env('GROQ_API_KEY');

        // 3. Fallback message if the key isn't set yet
        if (!$apiKey || str_contains($apiKey, 'YOUR_')) {
            return response()->json([
                'reply' => "I'm currently in 'Offline Mode' because my AI brain (API Key) isn't configured yet. Please add a valid GROQ_API_KEY to your .env file to enable live responses!"
            ]);
        }

        // 4. Set the "personality" and information the AI needs
        $systemPrompt = "You are NutriSense AI, a helpful and encouraging health & nutrition advisor. Keep responses concise, supportive and evidence-based. " . ($validated['context'] ?? '');

        try {
            // 5. Fire a POST request to Groq's API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(10)->post('https://api.groq.com/openai/v1/chat/completions', [
                        'model' => 'llama-3.3-70b-versatile', // We use Llama 3.3 for high quality results
                        'messages' => [
                            ['role' => 'system', 'content' => $systemPrompt],
                            ['role' => 'user', 'content' => $validated['message']]
                        ],
                        'max_tokens' => 512,
                        'temperature' => 0.7
                    ]);

            // 6. If successful, extract the text from the AI's response format
            if ($response->successful()) {
                return response()->json(['reply' => $response->json('choices')[0]['message']['content'] ?? 'AI response unavailable.']);
            }

            // 7. If the API returned an error (e.g. invalid key or rate limit)
            return response()->json([
                'reply' => "I'm having trouble connecting to the AI service (Groq). Error: " . ($response->json('error.message') ?? 'Unknown API Error')
            ]);
        } catch (\Exception $e) {
            // 8. If the connection failed entirely (e.g. no internet)
            return response()->json([
                'reply' => "Connection timed out. Please check your internet or API configuration."
            ]);
        }
    }
}