<?php

declare(strict_types=1);

namespace App\Exception;

class CurrencyIsNotAvailableException extends \InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('One of the currencies is not available.');
    }
}
