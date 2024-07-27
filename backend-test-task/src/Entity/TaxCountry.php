<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TaxCountryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaxCountryRepository::class)]
class TaxCountry implements EntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "SEQUENCE")]
    #[ORM\SequenceGenerator(sequenceName: "tax_country_id_seq")]
    #[ORM\Column(type: "integer")]
    private readonly int $id;

    #[ORM\Column(type: "string", length: 255)]
    private string $country;

    #[ORM\Column(type: "decimal", scale: 2)]
    private float $tax;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;
        return $this;
    }

    public function getTax(): ?float
    {
        return $this->tax;
    }

    public function setTax(float $tax): self
    {
        $this->tax = $tax;
        return $this;
    }
}
