<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Repository\TaxCountryRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TaxCountryFixtures extends Fixture
{
    private const array TAX_COUNTRY_LIST = [
        'Germany' => 19,
        'Italy' => 22,
        'France' => 20,
        'Greece' => 24,
    ];

    public function __construct(private readonly TaxCountryRepository $taxCountryRepository)
    {}

    final public function load(ObjectManager $manager): void
    {
        foreach (self::TAX_COUNTRY_LIST as $country => $tax) {
            $this->taxCountryRepository->createTaxCountry($country, $tax);
        }
    }
}
