<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;

class GeminiReceiptExtractor
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
    }

    /**
     * Ekstrak data dari gambar menggunakan prompt dan isi form.
     *
     * @param string $filePath Path ke file gambar.
     * @param string $prompt Prompt untuk Gemini AI.
     * @param callable $set Callback untuk mengisi field form.
     */
    public function extractAndFill(string $filePath, string $prompt, callable $set): void
    {
        if (!$this->apiKey) {
            Notification::make()->title('Konfigurasi Error')->body('GEMINI_API_KEY tidak ditemukan.')->danger()->send();
            return;
        }

        try {
            $imageData = base64_encode(file_get_contents($filePath));
            $imageMimeType = mime_content_type($filePath);

            $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                            ['inline_data' => ['mime_type' => $imageMimeType, 'data' => $imageData]]
                        ]
                    ]
                ]
            ]);

            if (!$response->successful()) {
                Log::error('Gemini API Error: ' . $response->body());
                Notification::make()->title('Ekstraksi Gagal')->body('Gagal terhubung ke layanan AI.')->danger()->send();
                return;
            }

            $resultText = $response->json('candidates.0.content.parts.0.text', '');
            preg_match('/\{(?:[^{}]|(?R))*\}/', $resultText, $matches);

            if (empty($matches[0])) {
                Notification::make()->title('Ekstraksi Gagal')->body('Tidak dapat menemukan format JSON pada respons AI.')->warning()->send();
                return;
            }

            $data = json_decode($matches[0], true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Notification::make()->title('Ekstraksi Gagal')->body('Respons AI bukan JSON yang valid.')->warning()->send();
                return;
            }

            // Isi form dengan data yang diekstrak
            if (isset($data['jenis_bbm'])) $set('jenis_bbm', $data['jenis_bbm']);
            if (isset($data['volume'])) $set('volume', $data['volume']);
            if (isset($data['total_biaya'])) $set('biaya_bbm', $data['total_biaya']);

            Notification::make()->title('Ekstraksi Berhasil')->body('Data dari struk berhasil diisi.')->success()->send();

        } catch (\Exception $e) {
            Log::error('Gemini Extraction Exception: ' . $e->getMessage());
            Notification::make()->title('Error')->body('Terjadi kesalahan saat memproses gambar.')->danger()->send();
        }
    }

    /**
     * Ekstrak hanya jumlah biaya dari struk (contoh: untuk tol).
     *
     * @param string $storagePath Path file di storage Laravel.
     * @return int|null
     */
    public function extractAmount(string $filePath): ?int
    {
        if (!$this->apiKey) {
            Notification::make()->title('Konfigurasi Error')->body('GEMINI_API_KEY tidak ditemukan.')->danger()->send();
            return null;
        }

        $prompt = "Anda adalah sistem OCR. Dari gambar struk ini, temukan jumlah total pembayaran. Berikan jawaban HANYA berupa angka saja, tanpa teks, format, atau simbol. Contoh: 55000";

        try {
            $imageData = base64_encode(file_get_contents($filePath));
            $imageMimeType = mime_content_type($filePath);

            $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                            ['inline_data' => ['mime_type' => $imageMimeType, 'data' => $imageData]]
                        ]
                    ]
                ],
                 'generationConfig' => [
                    'response_mime_type' => 'application/json',
                ]
            ]);

            if (!$response->successful()) {
                Log::error('Gemini API Error for Amount Extraction: ' . $response->body());
                Notification::make()->title('Ekstraksi Gagal')->body('Gagal terhubung ke layanan AI.')->danger()->send();
                return null;
            }

            $resultText = $response->json('candidates.0.content.parts.0.text', '');
            
            // Mencari angka dalam teks, karena respons mungkin tidak JSON murni
            preg_match('/\d+/', $resultText, $matches);

            if (empty($matches[0])) {
                Notification::make()->title('Ekstraksi Gagal')->body('Tidak dapat menemukan angka pada respons AI.')->warning()->send();
                return null;
            }

            return (int) $matches[0];

        } catch (\Exception $e) {
            Log::error('Gemini Amount Extraction Exception: ' . $e->getMessage());
            Notification::make()->title('Error')->body('Terjadi kesalahan saat memproses gambar.')->danger()->send();
            return null;
        }
    }
}

?>
