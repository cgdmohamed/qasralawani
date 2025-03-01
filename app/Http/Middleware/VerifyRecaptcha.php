<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;


class VerifyRecaptcha
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $recaptchaToken = $request->input('g-recaptcha-response');
        if (!$recaptchaToken) {
            return response()->json(['error' => 'reCAPTCHA validation failed'], 403);
        }

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $recaptchaToken,
            'remoteip' => $request->ip(),
        ]);

        $data = $response->json();

        if (!$data['success'] || $data['score'] < 0.5) {
            return response()->json(['error' => 'Suspicious activity detected'], 403);
        }

        return $next($request);
    }
}
