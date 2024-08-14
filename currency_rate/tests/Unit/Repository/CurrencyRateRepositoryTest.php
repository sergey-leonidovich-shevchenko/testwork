<?php

declare(strict_types=1);

namespace Tests\Unit\Repository;

use App\Entity\CurrencyRate;
use App\Repository\CurrencyRateRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CurrencyRateRepositoryTest extends KernelTestCase
{
    private readonly CurrencyRateRepository $currencyRateRepository;

    final protected function setUp(): void
    {
        parent::setUp();
        $this->currencyRateRepository = static::getContainer()->get(CurrencyRateRepository::class);
    }

    final public function testFindOneByCurrencyCode(): void
    {
        $currencyRate = $this->currencyRateRepository->findOneByCurrencyCode('USD');
        $currencyRateFromDB = $this->currencyRateRepository->find($currencyRate->getId());

        $this->assertInstanceOf(CurrencyRate::class, $currencyRateFromDB);
        $this->assertEquals($currencyRate->getRate(), $currencyRateFromDB->getRate());
        $this->assertEquals($currencyRate->getCurrencyCode(), $currencyRateFromDB->getCurrencyCode());
        $this->assertEquals($currencyRate->getDate(), $currencyRateFromDB->getDate());
    }
}
