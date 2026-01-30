<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    /**
     * Send a WhatsApp message using the Watzap API.
     *
     * @param string $numberKey
     * @param string $recipientNumber
     * @param string $message
     * @return void
     */
    public function sendMessage(string $numberKey, string $recipientNumber, string $message): void
    {
        $apiKey = env('WATZAP_API_KEY');

        if (!$apiKey) {
            Log::error('WATZAP_API_KEY is not set in the .env file.');
            return;
        }

        try {
            $response = Http::post('https://api.watzap.id/v1/send_message', [
                'api_key' => $apiKey,
                'number_key' => $numberKey,
                'phone_no' => $recipientNumber,
                'message' => $message,
            ]);

            if ($response->failed()) {
                Log::error('Failed to send WhatsApp message.', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
            } else {
                Log::info('WhatsApp message sent successfully.', [
                    'response' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Exception caught while sending WhatsApp message: ' . $e->getMessage());
        }
    }
}
