<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Coupon;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    public function showCoupon(Request $request)
    {
        // We assume the user’s phone is in session or we fetch from DB
        // For a small project, you might keep phone in session after verifying OTP
        // e.g., session('phone_number') or pass phone in a query param

        $phoneNumber = session('phone_number'); // example approach
        if (!$phoneNumber) {
            // Or handle differently if phone number is not in session
            return redirect()->route('otp.request.form')->withErrors('Phone number missing');
        }

        $user = User::where('phone_number', $phoneNumber)->first();
        if (!$user) {
            return redirect()->route('otp.request.form')->withErrors('User not found');
        }

        // Check if already claimed
        if ($user->has_claimed_coupon) {
            // Show a message that they already have a coupon
            return view('coupon.already_claimed');
        }

        // Else find an unused coupon
        $coupon = Coupon::where('is_used', false)->first();

        if (!$coupon) {
            // No coupons left
            return view('coupon.no_coupons');
        }

        // Mark coupon as used
        $coupon->is_used = true;
        $coupon->used_by = $user->id;
        $coupon->save();

        // Mark user as having claimed
        $user->has_claimed_coupon = true;
        $user->save();

        // Send coupon code via SMS (pseudo-code)
        /*
        Http::post('https://api.dreams.sa/send', [
            'phone_number' => $phoneNumber,
            'message' => "Your coupon code is: {$coupon->code}"
        ]);
        */

        // Return a view with the coupon code
        return view('coupon.show', ['couponCode' => $coupon->code]);
    }
}
