<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

class EmailService
{
    final public function sendEmail(string $to, string $subject, string $body): void
    {
        // Логика отправки email...
    }
}
