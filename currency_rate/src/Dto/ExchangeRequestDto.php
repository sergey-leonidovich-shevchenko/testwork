<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

readonly class ExchangeRequestDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 3)]
        private string $fromCurrency,

        #[Assert\NotBlank]
        #[Assert\Length(max: 3)]
        private string $toCurrency,

        #[Assert\NotBlank]
        #[Assert\Positive]
        private float $amount
    ) {
    }

    final public function getFromCurrency(): string
    {
        return $this->fromCurrency;
    }

    final public function getToCurrency(): string
    {
        return $this->toCurrency;
    }

    final public function getAmount(): float
    {
        return $this->amount;
    }
}
