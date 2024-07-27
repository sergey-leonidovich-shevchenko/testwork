<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CouponRepository;

#[ORM\Entity(repositoryClass: CouponRepository::class)]
class Coupon implements EntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "SEQUENCE")]
    #[ORM\Column(type: "integer")]
    private readonly int $id;

    #[ORM\Column(type: "string", length: 255)]
    private string $code;

    #[ORM\Column(type: "decimal", scale: 2, nullable: true)]
    private ?float $discountAmount = null;

    #[ORM\Column(type: "decimal", scale: 2, nullable: true)]
    private ?float $discountPercentage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function getDiscountAmount(): ?float
    {
        return $this->discountAmount;
    }

    public function setDiscountAmount(?float $discountAmount): self
    {
        $this->discountAmount = $discountAmount;
        return $this;
    }

    public function getDiscountPercentage(): ?float
    {
        return $this->discountPercentage;
    }

    public function setDiscountPercentage(?float $discountPercentage): self
    {
        $this->discountPercentage = $discountPercentage;
        return $this;
    }
}
