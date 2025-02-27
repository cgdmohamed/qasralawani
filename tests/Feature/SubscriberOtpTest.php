<?php

namespace Tests\Feature;

use App\Models\Subscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class SubscriberOtpTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that an invalid phone number fails validation.
     */
    public function test_otp_request_requires_valid_phone_number()
    {
        $response = $this->post(route('otp.request'), [
            'name'         => 'Test User',
            'email'        => 'test@example.com',
            'phone_number' => '1234567890', // invalid: does not start with '05' or '+9665'
        ]);

        $response->assertSessionHasErrors('phone_number');
    }

    /**
     * Test that a valid OTP request creates a subscriber and sets OTP fields.
     */
    public function test_otp_request_creates_subscriber_and_sets_otp_fields()
    {
        $phone = '0563083357'; // Valid: starts with '05' and 10 digits.
        $data = [
            'name'         => 'Test Subscriber',
            'email'        => 'subscriber@example.com',
            'phone_number' => $phone,
        ];

        $response = $this->post(route('otp.request'), $data);

        // Check that the user is redirected to the OTP verification page.
        $response->assertRedirect(route('otp.verify.form'));
        $response->assertSessionHas('phone_number', $phone);

        // Confirm a subscriber record was created with the provided data.
        $this->assertDatabaseHas('subscribers', [
            'phone_number' => $phone,
            'name'         => 'Test Subscriber',
            'email'        => 'subscriber@example.com',
        ]);

        // Confirm OTP fields are set.
        $subscriber = Subscriber::where('phone_number', $phone)->first();
        $this->assertNotNull($subscriber->otp_code);
        $this->assertNotNull($subscriber->otp_expires_at);
    }

    /**
     * Test that rate limiting is enforced on OTP requests.
     */
    public function test_rate_limiting_for_otp_requests()
    {
        $phone = '0563083357';
        $data = [
            'name'         => 'Rate Limited User',
            'email'        => 'rate@example.com',
            'phone_number' => $phone,
        ];

        // Clear any previous rate limiting data for this phone.
        RateLimiter::clear('otp:' . $phone);

        // Send 3 valid OTP requests.
        for ($i = 0; $i < 3; $i++) {
            $this->post(route('otp.request'), $data);
        }

        // The 4th request should be rate limited.
        $response = $this->post(route('otp.request'), $data);
        $response->assertSessionHasErrors('phone_number');
    }
}
