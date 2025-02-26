<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
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

    public function requestOtp(Request $request,SmsService $smsService)
    {
        // 1. Validate phone format
        $request->validate([
            'phone_number' => 'required|regex:/^(05|\+9665)\d{8}$/'
        ]);

        $phoneNumber = $request->input('phone_number');

        // 2. Implement Rate Limiting before generating/sending OTP
        $this->checkRateLimit($phoneNumber);

        // 3. Generate OTP
        $otp = rand(100000, 999999);

        // 4. Save OTP to user
        $smsService->sendSms($request->phone_number, "Your OTP is {$otp}");

        $user = User::firstOrNew(['phone_number' => $phoneNumber]);
        $user->otp_code = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(5);
        $user->save();

        // 5. Send SMS using Dreams.sa
        //    We use the service class we created
        $message = "Your OTP code is: {$otp}";
        $smsResponse = $this->smsService->sendSms($phoneNumber, $message);

        // Optionally handle $smsResponse if needed (e.g., log or check errors)

        // 6. Redirect or respond
        return redirect()->route('otp.verify.form')
                         ->with('phone_number', $phoneNumber);
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
