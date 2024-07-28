<?php

declare(strict_types=1);

namespace App\Tests\Unit\Validator\Constraint;

use App\Validator\Constraints\ExistingProduct;
use App\Validator\Constraints\ExistingProductValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;
use App\Repository\ProductRepository;
use App\Entity\Product;

class ExistingProductValidatorTest extends ConstraintValidatorTestCase
{
    private ProductRepository $productRepository;

    final protected function createValidator(): ExistingProductValidator
    {
        $this->productRepository = $this->createMock(ProductRepository::class);
        return new ExistingProductValidator($this->productRepository);
    }

    final public function testExistingProduct(): void
    {
        $product = new Product();
        $this->productRepository->method('find')->willReturn($product);

        $this->validator->validate(1, new ExistingProduct());

        $this->assertNoViolation();
    }

    final public function testNonExistingProduct(): void
    {
        $this->productRepository->method('find')->willReturn(null);

        $constraint = new ExistingProduct();
        $this->validator->validate(1, $constraint);

        $this->buildViolation($constraint->message)->setParameter('{{ value }}', '1')->assertRaised();
    }
}
