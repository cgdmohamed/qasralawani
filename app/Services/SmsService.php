<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SmsService
{
    /**
     * Send an SMS using the Dreams.sa SMS API.
     *
     * @param string $phoneNumber Recipient phone number (e.g. "9665XXXXXXXX")
     * @param string $message The message to send
     * @param array $options Optional parameters (e.g. ['date' => 'YYYY-MM-DD', 'time' => 'HH:MM:SS'])
     * @return mixed
     */
    public function sendSms(string $phoneNumber, string $message, array $options = [])
    {
        // Get configuration values
        $user       = config('services.dreams.user');
        $secret_key = config('services.dreams.secret_key');
        $sender     = config('services.dreams.sender');
        $apiUrl     = config('services.dreams.api_url');

        // Build query parameters
        $params = [
            'user'       => $user,
            'secret_key' => $secret_key,
            'to'         => $phoneNumber,
            'message'    => $message,
            'sender'     => $sender,
            'is_dlr'     => 1,  // request delivery report
        ];

        // Merge any optional scheduling parameters (if provided)
        if (!empty($options)) {
            $params = array_merge($params, $options);
        }

        // Use a GET request as per the API docs.
        $response = Http::get($apiUrl, $params);

        // Optionally log or inspect $response->body() for debugging.
        return $response->body();
    }
}
