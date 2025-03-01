<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscriber;
use App\Models\Coupon;
use App\Services\SmsService;

class CouponController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }
    /*
    public function showCoupon(Request $request)
    {
        // Retrieve the phone number from session (set during OTP verification)
        $phoneNumber = session('phone_number');
        if (!$phoneNumber) {
            return redirect()->route('otp.request.form')->withErrors('Phone number missing');
        }

        // Look up the subscriber by phone number.
        $subscriber = Subscriber::where('phone_number', $phoneNumber)->first();
        if (!$subscriber) {
            return redirect()->route('otp.request.form')->withErrors('User not found');
        }

        // If coupon code is already in session, fetch that coupon.
        if (session()->has('coupon_code')) {
            $couponCode = session('coupon_code');
            // Make sure to retrieve coupon record if needed.
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon) {
                return view('coupon.show', ['couponCode' => $coupon->code]);
            }
        }

        // Check if the subscriber has already been assigned a coupon.
        $coupon = Coupon::where('used_by', $subscriber->id)->first();
        if ($coupon) {
            session(['coupon_code' => $coupon->code]);
            return view('coupon.show', ['couponCode' => $coupon->code]);
        }

        // Retrieve an unused coupon.
        $coupon = Coupon::where('is_used', false)->first();
        if (!$coupon) {
            return view('coupon.no_coupons');
        }

        // Mark coupon as used and associate it with the subscriber.
        $coupon->is_used = true;
        $coupon->used_by = $subscriber->id;
        $coupon->save();

        // Mark the subscriber as having claimed a coupon.
        $subscriber->has_claimed_coupon = true;
        $subscriber->save();

        // Store the coupon code in the session.
        session(['coupon_code' => $coupon->code]);

        // Send coupon code via SMS.
        $smsMessage = "Congratulations! Your coupon code is: {$coupon->code}";
        $this->smsService->sendSms($subscriber->phone_number, $smsMessage);

        // Display the coupon code page.
        return view('coupon.show', ['couponCode' => $coupon->code]);
    }
*/
    public function showCoupon(Request $request)
    {
        // Retrieve the phone number from session (set after OTP verification)
        $phoneNumber = session('phone_number');
        if (!$phoneNumber) {
            return redirect()->route('otp.request.form')->withErrors('Phone number missing');
        }

        // Look up the subscriber by phone number.
        $subscriber = Subscriber::where('phone_number', $phoneNumber)->first();
        if (!$subscriber) {
            return redirect()->route('otp.request.form')->withErrors('User not found');
        }

        // Check if the subscriber already has a coupon assigned.
        $coupon = Coupon::where('used_by', $subscriber->id)->first();
        if ($coupon) {
            // Coupon already assigned—no need to send a new OTP or reassign.
            return view('coupon.show', ['couponCode' => $coupon->code]);
        }

        // Otherwise, assign a new coupon.
        $coupon = Coupon::where('is_used', false)->first();
        if (!$coupon) {
            return view('coupon.no_coupons');
        }

        // Mark the coupon as used and associate it with the subscriber.
        $coupon->is_used = true;
        $coupon->used_by = $subscriber->id;
        $coupon->save();

        // Mark the subscriber as having claimed a coupon.
        $subscriber->has_claimed_coupon = true;
        $subscriber->save();

        // Send the coupon code via SMS.
        $smsMessage = "Congratulations! Your coupon code is: {$coupon->code}";
        $this->smsService->sendSms($subscriber->phone_number, $smsMessage);

        // Display the coupon code page.
        return view('coupon.show', ['couponCode' => $coupon->code]);
    }
}
