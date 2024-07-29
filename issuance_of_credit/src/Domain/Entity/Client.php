<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use Symfony\Component\Validator\Constraints as Assert;


class Client
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        private string $firstName,

        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        private string $lastName,

        #[Assert\NotBlank]
        #[Assert\Range(min: 18, max: 60)]
        private int $age,

        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        private string $city,

        #[Assert\NotBlank]
        #[Assert\Choice(choices: ['CA', 'NY', 'NV'])]
        private string $state,

        #[Assert\NotBlank]
        #[Assert\Length(max: 10)]
        private string $zip,

        #[Assert\NotBlank]
        #[Assert\Length(max: 11)]
        private string $ssn,

        #[Assert\NotBlank]
        #[Assert\Range(min: 300, max: 850)]
        private int $fico,

        #[Assert\NotBlank]
        #[Assert\Email]
        private string $email,

        #[Assert\NotBlank]
        #[Assert\Length(max: 20)]
        private string $phone,

        #[Assert\NotBlank]
        #[Assert\GreaterThanOrEqual(value: 1000)]
        private float $monthlyIncome,
    ) {}

    final public function getFirstName(): string
    {
        return $this->firstName;
    }

    final public function setFirstName(string $firstName): Client
    {
        $this->firstName = $firstName;
        return $this;
    }

    final public function getLastName(): string
    {
        return $this->lastName;
    }

    final public function setLastName(string $lastName): Client
    {
        $this->lastName = $lastName;
        return $this;
    }

    final public function getAge(): int
    {
        return $this->age;
    }

    final public function setAge(int $age): Client
    {
        $this->age = $age;
        return $this;
    }

    final public function getCity(): string
    {
        return $this->city;
    }

    final public function setCity(string $city): Client
    {
        $this->city = $city;
        return $this;
    }

    final public function getState(): string
    {
        return $this->state;
    }

    final public function setState(string $state): Client
    {
        $this->state = $state;
        return $this;
    }

    final public function getZip(): string
    {
        return $this->zip;
    }

    final public function setZip(string $zip): Client
    {
        $this->zip = $zip;
        return $this;
    }

    final public function getSsn(): string
    {
        return $this->ssn;
    }

    final public function setSsn(string $ssn): Client
    {
        $this->ssn = $ssn;
        return $this;
    }

    final public function getFico(): int
    {
        return $this->fico;
    }

    final public function setFico(int $fico): Client
    {
        $this->fico = $fico;
        return $this;
    }

    final public function getEmail(): string
    {
        return $this->email;
    }

    final public function setEmail(string $email): Client
    {
        $this->email = $email;
        return $this;
    }

    final public function getPhone(): string
    {
        return $this->phone;
    }

    final public function setPhone(string $phone): Client
    {
        $this->phone = $phone;
        return $this;
    }

    final public function getMonthlyIncome(): float
    {
        return $this->monthlyIncome;
    }

    final public function setMonthlyIncome(float $monthlyIncome): Client
    {
        $this->monthlyIncome = $monthlyIncome;
        return $this;
    }
}
