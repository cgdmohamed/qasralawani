<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SmsService
{
    public function sendSms($phoneNumber, $message)
    {
        $apiUrl = config('services.dreams.api_url');
        $apiKey = config('services.dreams.api_key');
        $senderId = config('services.dreams.sender_id');

        // According to Dreams.sa docs, you may need specific parameters for sending an SMS.
        // This is a pseudo-example based on typical REST SMS gateways.
        // Adjust keys (like 'to', 'text', 'sender') as required by Dreams.sa’s actual API.
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Accept' => 'application/json',
        ])->post($apiUrl, [
            'to' => $phoneNumber,
            'text' => $message,
            'sender' => $senderId,
        ]);

        // Check response or return it
        return $response->json();
    }
}
