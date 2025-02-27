<?php

namespace Tests\Unit;

use App\Helpers\PhoneValidator;
use PHPUnit\Framework\TestCase;

class PhoneValidatorTest extends TestCase
{
    public function test_valid_phone_starts_with_05()
    {
        $this->assertTrue(PhoneValidator::isValidKsaPhone('0563083357'));
    }

    public function test_invalid_phone_wrong_prefix()
    {
        $this->assertFalse(PhoneValidator::isValidKsaPhone('1234567890'));
    }

    public function test_valid_phone_starts_with_plus9665()
    {
        $this->assertTrue(PhoneValidator::isValidKsaPhone('+96653083357'));
    }

    public function test_invalid_phone_length_for_05()
    {
        $this->assertFalse(PhoneValidator::isValidKsaPhone('056308335')); // 9 digits, should be 10
    }

    public function test_invalid_phone_length_for_plus9665()
    {
        $this->assertFalse(PhoneValidator::isValidKsaPhone('+9665308335')); // 12 characters, should be 13
    }
}
