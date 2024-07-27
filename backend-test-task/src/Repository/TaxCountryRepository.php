<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TaxCountry;
use Doctrine\Persistence\ManagerRegistry;

class TaxCountryRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TaxCountry::class);
    }

    final public function createTaxCountry(string $country, float $tax): TaxCountry
    {
        $taxCountry = (new TaxCountry())->setCountry($country)->setTax($tax);
        $this->save($taxCountry);

        return $taxCountry;
    }

    public function getTaxByCountry(string $country): float
    {
        $tax = $this->createQueryBuilder('tc')
            ->select('tc.tax')
            ->where('tc.country = :country')
            ->setParameter('country', $country)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        if (!$tax) {
            return 0;
        }

        return (float)current(current($tax));
    }
}
