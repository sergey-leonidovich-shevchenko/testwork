<?php

declare(strict_types=1);

namespace App\Enum;

enum CurrencyEnum: string
{
    case EUR = 'EUR';
    case USD = 'USD';
    case GBP = 'GBP';
}
