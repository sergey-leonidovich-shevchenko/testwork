<?php

declare(strict_types=1);

namespace App\UseCase\Command;

use App\Service\CurrencyRateService;

readonly class CurrencyRateUpdateUseCase
{
    public function __construct(
        private CurrencyRateService $currencyRateService,
    ) {
    }

    final public function execute(): void
    {
        $this->currencyRateService->updateRates();
    }
}
