<?php

declare(strict_types=1);

namespace App\Domain\Entity;

class CreditProduct
{
    public function __construct(
        private string $name,
        private int $term,
        private float $interestRate,
        private float $amount
    ) {}

    final public function getName(): string
    {
        return $this->name;
    }

    final public function setName(string $name): CreditProduct
    {
        $this->name = $name;
        return $this;
    }

    final public function getTerm(): int
    {
        return $this->term;
    }

    final public function setTerm(int $term): CreditProduct
    {
        $this->term = $term;
        return $this;
    }

    final public function getInterestRate(): float
    {
        return $this->interestRate;
    }

    final public function setInterestRate(float $interestRate): CreditProduct
    {
        $this->interestRate = $interestRate;
        return $this;
    }

    final public function getAmount(): float
    {
        return $this->amount;
    }

    final public function setAmount(float $amount): CreditProduct
    {
        $this->amount = $amount;
        return $this;
    }
}
