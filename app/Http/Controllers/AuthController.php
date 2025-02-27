<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subscriber;
use Carbon\Carbon;

use App\Services\SmsService;
use Illuminate\Support\Facades\RateLimiter;


class AuthController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function showPhoneForm()
    {
        return view('auth.phone');
    }

    public function requestOtp(Request $request)
    {
        // Validate input: name and phone are required; email is optional.
        $request->validate([
            'phone_number' => ['required', function ($attribute, $value, $fail) {
                if (!(str_starts_with($value, '05') || str_starts_with($value, '+9665'))) {
                    return $fail("The {$attribute} must start with '05' or '+9665'.");
                }
                if (str_starts_with($value, '05') && strlen($value) !== 10) {
                    return $fail("The {$attribute} must be 10 digits long if it starts with '05'.");
                }
                if (str_starts_with($value, '+9665') && strlen($value) !== 13) {
                    return $fail("The {$attribute} must be 13 digits long if it starts with '+9665'.");
                }
            }],
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        $phoneNumber = $request->input('phone_number');

        // Rate limiting: e.g., 3 attempts per 60 seconds per phone number.
        $key = 'otp:' . $phoneNumber;
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'phone_number' => "Too many OTP requests. Try again in {$seconds} seconds."
            ]);
        }
        RateLimiter::hit($key, 60);

        // Generate OTP (6-digit)
        $otp = rand(100000, 999999);

        // Retrieve or create subscriber by phone number.
        $subscriber = Subscriber::firstOrNew(['phone_number' => $phoneNumber]);
        if (!$subscriber->exists) {
            $subscriber->name  = $request->input('name');
            $subscriber->email = $request->input('email'); // Will be null if not provided.
        }
        $subscriber->otp_code       = $otp;
        $subscriber->otp_expires_at = Carbon::now()->addMinutes(5);
        $subscriber->save();

        // Prepare the OTP message.
        $message = "Your OTP code is: {$otp}";

        // Send SMS using your SMS service.
        $smsResponse = $this->smsService->sendSms($phoneNumber, $message);
        // Optionally log or inspect $smsResponse for debugging.

        // Redirect to the OTP verification form, carrying the phone number.
        return redirect()->route('otp.verify.form')->with('phone_number', $phoneNumber);
    }

    /**
     * A helper method to implement rate limiting
     * This prevents too many OTP requests in a short time
     */
    private function checkRateLimit($phoneNumber)
    {
        // Key can be phone-based or IP-based or combination
        $rateLimitKey = 'otp-request:' . $phoneNumber;

        // If too many attempts
        if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            // Return an error or throw an exception
            abort(429, 'Too many OTP requests. Please try again in ' . $seconds . ' seconds.');
        }

        // Record this attempt, expire in X seconds/minutes
        RateLimiter::hit($rateLimitKey, 60);  // e.g. 60 seconds
    }

    public function showOtpForm()
    {
        // Show a form where user inputs the OTP
        return view('auth.otp');
    }
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone_number' => 'required',
            'otp_code' => 'required|numeric',
        ]);

        $user = User::where('phone_number', $request->phone_number)->first();

        if (!$user) {
            return back()->withErrors(['phone_number' => 'Phone not found.']);
        }

        // Optionally rate limit attempts to verify
        $rateLimitKey = 'otp-verify:' . $user->phone_number;
        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            return back()->withErrors(['otp_code' => "Too many attempts. Try again in {$seconds}s"]);
        }
        RateLimiter::hit($rateLimitKey, 300); // e.g., 5 min decay

        // Now check OTP
        if ($user->otp_code == $request->otp_code && $user->otp_expires_at && $user->otp_expires_at->isFuture()) {
            // OTP valid -> reset, proceed
            $user->otp_code = null;
            $user->otp_expires_at = null;
            $user->save();

            // Clear rate limit attempts on success
            RateLimiter::clear($rateLimitKey);

            return redirect()->route('coupon.show')->with(['phone_number' => $user->phone_number]);
        }

        // OTP invalid
        return back()->withErrors(['otp_code' => 'Invalid or expired OTP.']);
    }
}
