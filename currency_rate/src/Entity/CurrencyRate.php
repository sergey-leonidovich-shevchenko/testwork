<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CurrencyRateRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurrencyRateRepository::class)]
class CurrencyRate
{
    public function __construct(
        #[ORM\Id]
        #[ORM\GeneratedValue(strategy: "SEQUENCE")]
        #[ORM\Column(type: 'string', length: 3)]
        private string $currencyCode,

        #[ORM\Column(type: 'decimal', precision: 15, scale: 6)]
        private float $rate,

        #[ORM\Column(type: 'datetime')]
        private \DateTime $date
    ) {
    }

    final public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    final public function setCurrencyCode(string $currencyCode): CurrencyRate
    {
        $this->currencyCode = $currencyCode;
        return $this;
    }

    final public function getRate(): float
    {
        return $this->rate;
    }

    final public function setRate(float $rate): CurrencyRate
    {
        $this->rate = $rate;
        return $this;
    }

    final public function getDate(): \DateTime
    {
        return $this->date;
    }

    final public function setDate(\DateTime $date): CurrencyRate
    {
        $this->date = $date;
        return $this;
    }
}
