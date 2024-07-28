<?php

declare(strict_types=1);

namespace App\Dto;

use App\Validator\Constraints as CustomAssert;
use Symfony\Component\Validator\Constraints as Assert;

class ProductRequestDto
{
    #[Assert\NotBlank(groups: ['calculate', 'purchase'])]
    #[Assert\Type('numeric', groups: ['calculate', 'purchase'])]
    #[CustomAssert\ExistingProduct(groups: ['calculate', 'purchase'])]
    public int $product;

    #[Assert\NotBlank(groups: ['calculate', 'purchase'])]
    #[Assert\Type('string', groups: ['calculate', 'purchase'])]
    #[Assert\Length(min: 10, max: 10, groups: ['calculate', 'purchase'])]
    public string $taxNumber;

    #[Assert\When(
        expression: 'this.couponCode',
        constraints: [new Assert\Type('string', groups: ['calculate', 'purchase'])],
        groups: ['calculate', 'purchase']
    )]
    public ?string $couponCode;

    #[Assert\NotBlank(groups: ['purchase'])]
    #[Assert\Type('string', groups: ['purchase'])]
    public ?string $paymentProcessor;
}
