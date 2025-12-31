<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GeminiService
{
    protected $apiKey;

    // Model Gemini yang digunakan
    protected $baseUrl =
        'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent';

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
    }

    /* ============================================================
     * MAIN CHAT METHOD — WITH DOCUMENT CONTEXT
     * ============================================================ */
    public function chat(string $userMessage, array $history = [], array $documentPaths = [])
    {
        $contents = [];

        /* ------------------------------------------------------------
         * 1. Chat History (Text Only)
         * ------------------------------------------------------------ */
        foreach ($history as $msg) {
            $role = ($msg['role'] === 'user') ? 'user' : 'model';

            $contents[] = [
                'role' => $role,
                'parts' => [
                    ['text' => $msg['content']]
                ]
            ];
        }

        /* ------------------------------------------------------------
         * 2. Current User Message (PDF + System Prompt + Question)
         * ------------------------------------------------------------ */
        $currentParts = [];

        // A. Attach PDF Files (Base64 Inline Data)
        foreach ($documentPaths as $path) {
            $fullPath = Storage::disk('public')->path($path);

            if (file_exists($fullPath)) {
                $encoded = base64_encode(file_get_contents($fullPath));

                $currentParts[] = [
                    'inlineData' => [
                        'mimeType' => 'application/pdf',
                        'data' => $encoded,
                    ]
                ];
            }
        }

        // B. System Prompt + User Message
        $systemPrompt =
            "Anda adalah asisten AI profesional untuk aplikasi Symbiosis. " .
            "Gunakan dokumen PDF yang dilampirkan sebagai sumber utama jawaban. " .
            "Jika informasi tidak ditemukan di dokumen, jelaskan dengan jujur. " .
            "Jawaban harus menggunakan Bahasa Indonesia formal dan jelas. " .
            "Gunakan Markdown yang rapi.";

        $currentParts[] = [
            'text' => $systemPrompt . "\n\nPERTANYAAN USER: " . $userMessage,
        ];

        // Tambahkan ke payload
        $contents[] = [
            'role' => 'user',
            'parts' => $currentParts
        ];

        /* ------------------------------------------------------------
         * 3. Send Request to Gemini API
         * ------------------------------------------------------------ */
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '?key=' . $this->apiKey, [
                        'contents' => $contents,
                        'generationConfig' => [
                            'temperature' => 0.3,
                            'maxOutputTokens' => 4096,
                        ]
                    ]);

            if ($response->successful()) {
                $json = $response->json();

                return $json['candidates'][0]['content']['parts'][0]['text']
                    ?? 'Maaf, saya tidak dapat memproses respons.';
            }

            Log::error('Gemini API Error: ' . $response->body());
            return "Terjadi kesalahan AI: {$response->status()}. Pastikan model Gemini tersedia.";

        } catch (\Exception $e) {
            Log::error('Gemini Exception: ' . $e->getMessage());
            return 'Terjadi kesalahan sistem koneksi AI.';
        }
    }

    /* ============================================================
     * ESG CHAPTER GENERATION — FOR LONGER CONTENT
     * ============================================================ */
    public function generateEsgChapter(string $prompt, array $documentPaths = []): string
    {
        $currentParts = [];

        // A. Attach PDF Files (Base64 Inline Data) - Max 5 files to avoid token limits
        $attachedCount = 0;
        foreach ($documentPaths as $path) {
            if ($attachedCount >= 5)
                break; // Limit to 5 documents per call

            $fullPath = Storage::disk('public')->path($path);

            if (file_exists($fullPath)) {
                $fileSize = filesize($fullPath);
                // Skip files larger than 5MB to avoid API limits
                if ($fileSize > 5 * 1024 * 1024) {
                    continue;
                }

                $encoded = base64_encode(file_get_contents($fullPath));

                $currentParts[] = [
                    'inlineData' => [
                        'mimeType' => 'application/pdf',
                        'data' => $encoded,
                    ]
                ];
                $attachedCount++;
            }
        }

        // B. Add the prompt
        $currentParts[] = [
            'text' => $prompt,
        ];

        $contents = [
            [
                'role' => 'user',
                'parts' => $currentParts
            ]
        ];

        try {
            $response = Http::timeout(120)->withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '?key=' . $this->apiKey, [
                        'contents' => $contents,
                        'generationConfig' => [
                            'temperature' => 0.2, // Lower for more factual
                            'maxOutputTokens' => 8192, // Higher for longer content
                        ]
                    ]);

            if ($response->successful()) {
                $json = $response->json();

                return $json['candidates'][0]['content']['parts'][0]['text']
                    ?? 'Maaf, tidak dapat memproses respons.';
            }

            Log::error('Gemini ESG API Error: ' . $response->body());
            return "Terjadi kesalahan AI: {$response->status()}.";

        } catch (\Exception $e) {
            Log::error('Gemini ESG Exception: ' . $e->getMessage());
            throw $e; // Re-throw for the caller to handle
        }
    }
}
