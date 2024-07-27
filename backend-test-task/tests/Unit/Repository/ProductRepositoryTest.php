<?php

declare(strict_types=1);

namespace App\Tests\Unit\Repository;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductRepositoryTest extends KernelTestCase
{
    private readonly ProductRepository $productRepository;

    final public function setUp(): void
    {
        parent::setUp();
        $this->productRepository = static::getContainer()->get(ProductRepository::class);
    }

    final public function testCreateProduct(): void
    {
        $product = $this->productRepository->createProduct('Product 1', 100);
        $productFromDB = $this->productRepository->find($product->getId());

        $this->assertInstanceOf(Product::class, $productFromDB);
        $this->assertEquals($product->getName(), $productFromDB->getName());
        $this->assertEquals($product->getPrice(), $productFromDB->getPrice());
    }
}
