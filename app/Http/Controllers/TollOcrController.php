<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TollOcrController extends Controller
{
    public function extract(Request $request)
    {
        $request->validate([
            'struk' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $image = $request->file('struk');
        $imageData = base64_encode(file_get_contents($image->getRealPath()));

        $apiKey = config('services.gemini.api_key');

        if (!$apiKey) {
            return response()->json(['error' => 'GEMINI_API_KEY tidak dikonfigurasi'], 500);
        }

        // Prompt yang sangat detail agar Gemini tidak salah ambil saldo
        $prompt = "Ini adalah struk tol. Cari nominal 'Rp' yang merupakan tarif masuk/perjalanan. JANGAN ambil angka sisa saldo. Berikan jawaban HANYA berupa JSON: {\"jumlah_toll\": angka_tanpa_titik}";

        $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt],
                        [
                            'inline_data' => [
                                'mime_type' => $image->getMimeType(),
                                'data' => $imageData
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        if (!$response->successful()) {
            return response()->json(['error' => 'Gagal memproses gambar: ' . $response->status()], 500);
        }

        $result = $response->json();

        // Parsing text dari Gemini yang biasanya dalam format markdown json
        $textResponse = $result['candidates'][0]['content']['parts'][0]['text'];

        // Cari pola JSON dalam response
        preg_match('/\{(?:[^{}]|(?R))*\}/', $textResponse, $matches);

        if (empty($matches[0])) {
            return response()->json(['error' => 'Tidak dapat mengekstrak data dari response Gemini'], 500);
        }

        $jsonData = json_decode($matches[0], true);

        if (!$jsonData || !isset($jsonData['jumlah_toll'])) {
            return response()->json(['error' => 'Format response Gemini tidak valid'], 500);
        }

        // Return only the extracted amount for form auto-fill
        return response()->json([
            'jumlah_toll' => $jsonData['jumlah_toll']
        ]);
    }
}
