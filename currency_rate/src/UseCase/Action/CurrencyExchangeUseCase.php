<?php

declare(strict_types=1);

namespace App\UseCase\Action;

use App\Dto\ExchangeRequestDto;
use App\Service\ExchangeRateCalculator;

readonly class CurrencyExchangeUseCase
{
    public function __construct(private ExchangeRateCalculator $exchangeRateCalculator)
    {
    }

    final public function execute(ExchangeRequestDto $request): float
    {
        return $this->exchangeRateCalculator->calculate($request);
    }
}
