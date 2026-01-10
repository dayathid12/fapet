<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Http;

$apiKey = 'AIzaSyBnEeK2x66gsAr0ypwF_RQVbYkDdMR91XQ';

$prompt = "Say hello";

try {
    $response = Http::post('https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . $apiKey, [
        'contents' => [
            [
                'parts' => [
                    ['text' => $prompt]
                ]
            ]
        ],
        'generationConfig' => [
            'temperature' => 0.1,
            'topK' => 1,
            'topP' => 1,
            'maxOutputTokens' => 50,
        ]
    ]);

    if ($response->successful()) {
        $data = $response->json();
        echo "Success: " . json_encode($data, JSON_PRETTY_PRINT);
    } else {
        echo "Error: " . $response->status() . " - " . $response->body();
    }
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage();
}
