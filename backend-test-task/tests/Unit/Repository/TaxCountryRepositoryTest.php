<?php

declare(strict_types=1);

namespace App\Tests\Unit\Repository;

use App\Entity\TaxCountry;
use App\Repository\TaxCountryRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaxCountryRepositoryTest extends KernelTestCase
{
    private readonly TaxCountryRepository $taxCountryRepository;

    final public function setUp(): void
    {
        parent::setUp();
        $this->taxCountryRepository = static::getContainer()->get(TaxCountryRepository::class);
    }

    final public function testCreateTaxCountry(): void
    {
        $taxCountry = $this->taxCountryRepository->createTaxCountry('Germany', 19);
        $taxCountryFromDB = $this->taxCountryRepository->find($taxCountry->getId());

        $this->assertInstanceOf(TaxCountry::class, $taxCountryFromDB);
        $this->assertEquals($taxCountry->getCountry(), $taxCountryFromDB->getCountry());
        $this->assertEquals($taxCountry->getTax(), $taxCountryFromDB->getTax());
    }
}
