<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\ExchangeRequestDTO;
use App\Enum\CurrencyEnum;
use App\Repository\CurrencyRateRepository;

readonly class ExchangeRateCalculator
{
    public function __construct(private CurrencyRateRepository $currencyRateRepository)
    {
    }

    final public function calculate(ExchangeRequestDTO $request): float
    {
        $fromRate = $this->currencyRateRepository->findOneByCurrencyCode($request->getFromCurrency());
        $toRate = $this->currencyRateRepository->findOneByCurrencyCode($request->getToCurrency());

        if (!$fromRate || !$toRate) {
            throw new \InvalidArgumentException("One of the currencies is not available.");
        }

        // Если fromCurrency - базовая валюта, просто делим
        if ($request->getFromCurrency() === CurrencyEnum::EUR->value) {
            return $request->getAmount() * $toRate->getRate();
        }

        // Если toCurrency - базовая валюта, просто делим
        if ($request->getToCurrency() === CurrencyEnum::EUR->value) {
            return $request->getAmount() / $fromRate->getRate();
        }

        // Промежуточный расчет через базовую валюту (EUR)
        return $request->getAmount() / $fromRate->getRate() * $toRate->getRate();
    }
}
