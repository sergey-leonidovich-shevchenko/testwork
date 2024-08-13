<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\CurrencyRate;
use App\Repository\CurrencyRateRepository;
use Doctrine\ORM\EntityManagerInterface;

readonly class CurrencyRateService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CurrencyRateRepository $currencyRateRepository,
        private CurrencyDataProviderInterface $dataProvider,
    ) {
    }

    final public function updateRates(): void
    {
        $rates = $this->dataProvider->fetchRates();
        $date = new \DateTime();

        foreach ($rates as $currencyCode => $rate) {
            // TODO: move from foreach
            $currencyRate = $this->currencyRateRepository->findOneByCurrencyCode($currencyCode);

            if (!$currencyRate) {
                $currencyRate = new CurrencyRate($currencyCode, $rate, $date);
                $this->entityManager->persist($currencyRate);
            } else {
                $currencyRate->setRate($rate);
                $currencyRate->setDate($date);
            }
        }

        $this->entityManager->flush();
    }
}
