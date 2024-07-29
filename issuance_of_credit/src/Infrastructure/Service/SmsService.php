<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

class SmsService
{
    final public function sendSms(string $phoneNumber, string $message): void
    {
        // Логика отправки SMS...
    }
}
