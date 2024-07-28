<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use App\Repository\ProductRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ExistingProductValidator extends ConstraintValidator
{
    public function __construct(private readonly ProductRepository $productRepository)
    {}

    final public function validate($value, Constraint $constraint): void
    {
        if (null === $value || '' === $value) {
            return;
        }

        $product = $this->productRepository->find($value);
        if (!$product) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', (string)$value)
                ->addViolation();
        }
    }
}
