<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait SendsWhatsAppMessages
{
    /**
     * Send a WhatsApp message using Watzap.id API.
     *
     * @param string $number The recipient's phone number.
     * @param string $message The message content.
     * @return bool
     */
    public function sendWhatsAppMessage(string $number, string $message): bool
    {
        $apiKey = config('services.watzap.api_key');
        $sender = config('services.watzap.sender');

        if (!$apiKey || !$sender) {
            Log::error('Watzap API Key or Sender not configured.');
            return false;
        }

        try {
            $response = Http::post('https://api.watzap.id/v1/messages', [
                'api_key' => $apiKey,
                'sender' => $sender,
                'number' => $number,
                'message' => $message,
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp message sent successfully to ' . $number);
                return true;
            }

            Log::error('Failed to send WhatsApp message.', [
                'response_status' => $response->status(),
                'response_body' => $response->body(),
            ]);

            return false;
        } catch (\Exception $e) {
            Log::critical('Exception while sending WhatsApp message.', [
                'exception_message' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
