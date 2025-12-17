<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    protected $apiKey;

    public function __construct()
    {
        // Ambil API key dari .env
        $this->apiKey = env('FONNTE_API_KEY');
    }

    /* ============================================================
     * SEND WHATSAPP MESSAGE
     * ============================================================ */
    public function sendWhatsApp($target, $message)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
            ])->post('https://api.fonnte.com/send', [
                'target'      => $target,
                'message'     => $message,
                'countryCode' => '62',  // default Indonesia
            ]);

            if ($response->successful()) {
                Log::info('Fonnte Success: ' . $response->body());
                return true;
            }

            Log::error('Fonnte Error: ' . $response->body());
            return false;

        } catch (\Exception $e) {
            Log::error('Fonnte Exception: ' . $e->getMessage());
            return false;
        }
    }
}
