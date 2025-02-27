<?php

namespace App\Helpers;

class PhoneValidator
{
    public static function isValidKsaPhone(string $phone): bool
    {
        if (!(str_starts_with($phone, '05') || str_starts_with($phone, '+9665'))) {
            return false;
        }
        if (str_starts_with($phone, '05') && strlen($phone) !== 10) {
            return false;
        }
        if (str_starts_with($phone, '+9665') && strlen($phone) !== 13) {
            return false;
        }
        return true;
    }
}
