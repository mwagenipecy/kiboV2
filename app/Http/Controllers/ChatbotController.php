<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'conversation_history' => 'sometimes|array',
        ]);

        $apiKey = config('services.openai.key');
        $model = config('services.openai.model', 'gpt-4o-mini');
        $baseUrl = config('services.openai.base_url', 'https://api.openai.com/v1');

        if (empty($apiKey)) {
            return response()->json([
                'error' => 'OpenAI API key not configured'
            ], 500);
        }

        // Build conversation messages
        $conversationMessages = [
            [
                'role' => 'system',
                'content' => 'You are a helpful assistant for Kibo Auto, a vehicle marketplace in Tanzania. You help customers find vehicles (cars, trucks, bikes), sell their vehicles, find spare parts, and locate garage services. Be friendly, concise, and helpful. Respond in a conversational manner.'
            ]
        ];

        // Add conversation history if provided
        if ($request->has('conversation_history') && is_array($request->conversation_history)) {
            foreach ($request->conversation_history as $msg) {
                if (isset($msg['role']) && isset($msg['content'])) {
                    $conversationMessages[] = [
                        'role' => $msg['role'] === 'bot' ? 'assistant' : 'user',
                        'content' => $msg['content']
                    ];
                }
            }
        }

        // Add current user message
        $conversationMessages[] = [
            'role' => 'user',
            'content' => $request->message
        ];

        try {
            $response = Http::timeout(30)
                ->withToken($apiKey)
                ->post($baseUrl . '/chat/completions', [
                    'model' => $model,
                    'messages' => $conversationMessages,
                    'temperature' => 0.7,
                    'max_tokens' => 500,
                ]);

            if ($response->failed()) {
                Log::error('OpenAI API Error: ' . $response->body());
                return response()->json([
                    'error' => 'Failed to get response from AI service'
                ], 500);
            }

            $data = $response->json();
            
            if (!isset($data['choices'][0]['message']['content'])) {
                return response()->json([
                    'error' => 'Invalid response from AI service'
                ], 500);
            }

            return response()->json([
                'response' => $data['choices'][0]['message']['content']
            ]);

        } catch (\Exception $e) {
            Log::error('Chatbot Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'An error occurred while processing your request'
            ], 500);
        }
    }
}

