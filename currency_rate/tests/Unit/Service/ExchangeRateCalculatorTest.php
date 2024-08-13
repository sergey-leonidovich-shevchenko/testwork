<?php

declare(strict_types=1);

namespace Tests\Unit\Service;

use App\Dto\ExchangeRequestDTO;
use App\Entity\CurrencyRate;
use App\Enum\CurrencyEnum;
use App\Repository\CurrencyRateRepository;
use App\Service\ExchangeRateCalculator;
use PHPUnit\Framework\TestCase;

class ExchangeRateCalculatorTest extends TestCase
{
    final public function testCalculate(): void
    {
        $currencyRateRepository = $this->createMock(CurrencyRateRepository::class);
        $currencyRateRepository->method('findOneBy')
            ->willReturnCallback(function ($criteria) {
                $rates = [
                    CurrencyEnum::USD->value => new CurrencyRate(CurrencyEnum::USD->value, 1.2, new \DateTime()),
                    CurrencyEnum::GBP->value => new CurrencyRate(CurrencyEnum::GBP->value, 0.8, new \DateTime()),
                    CurrencyEnum::EUR->value => new CurrencyRate(CurrencyEnum::EUR->value, 1.0, new \DateTime()),
                ];

                return $rates[$criteria['currencyCode']] ?? null;
            });

        $calculator = new ExchangeRateCalculator($currencyRateRepository);

        // TODO: Use DataProvider
        $request = new ExchangeRequestDTO(CurrencyEnum::USD->value, CurrencyEnum::GBP->value, 100);
        $result = $calculator->calculate($request);
        $this->assertEquals(66.67, round($result, 2)); // 100 USD должны конвертироваться в 66.67 GBP

        $request = new ExchangeRequestDTO(CurrencyEnum::GBP->value, CurrencyEnum::USD->value, 100);
        $result = $calculator->calculate($request);
        $this->assertEquals(150, round($result, 2)); // 100 GBP должны конвертироваться в 150 USD

        $request = new ExchangeRequestDTO(CurrencyEnum::EUR->value, CurrencyEnum::USD->value, 100);
        $result = $calculator->calculate($request);
        $this->assertEquals(120, round($result, 2)); // 100 EUR должны конвертироваться в 120 USD
    }
}
