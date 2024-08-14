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
    final protected function setUp(): void
    {
        parent::setUp();

        $currencyRateRepository = $this->createMock(CurrencyRateRepository::class);
        $currencyRateRepository->method('findOneByCurrencyCode')
            ->willReturnCallback(function ($criteria) {
                $rates = [
                    CurrencyEnum::USD->value => new CurrencyRate(CurrencyEnum::USD->value, 1.2, new \DateTime()),
                    CurrencyEnum::GBP->value => new CurrencyRate(CurrencyEnum::GBP->value, 0.8, new \DateTime()),
                    CurrencyEnum::EUR->value => new CurrencyRate(CurrencyEnum::EUR->value, 1.0, new \DateTime()),
                ];

                return $rates[$criteria['currencyCode']] ?? null;
            });
        $this->calculator = new ExchangeRateCalculator($currencyRateRepository);
    }

    final public static function calculatePriceDataProvider(): \Generator
    {
        yield '100 USD must be convert to 66.67 GBP' => [
            'fromCurrency' => CurrencyEnum::USD->value,
            'toCurrency' => CurrencyEnum::GBP->value,
            'amount' => 100,
            'result' => 66.67,
        ];

        yield '100 GBP must be convert to 150 USD' => [
            'fromCurrency' => CurrencyEnum::GBP->value,
            'toCurrency' => CurrencyEnum::USD->value,
            'amount' => 100,
            'result' => 150.0,
        ];

        yield '100 EUR must be convert to 120 USD' => [
            'fromCurrency' => CurrencyEnum::EUR->value,
            'toCurrency' => CurrencyEnum::USD->value,
            'amount' => 100,
            'result' => 120.0,
        ];
    }

    final public function testCalculate(string $fromCurrency, string $toCurrency, int $amount, float $result): void
    {
        $request = new ExchangeRequestDTO($fromCurrency, $toCurrency, $amount);
        $this->assertEquals($result, \round($this->calculator->calculate($request), 2));
    }
}
