<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Repository\ProductRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function __construct(private readonly ProductRepository $productRepository)
    {}

    final public function load(ObjectManager $manager): void
    {
        $this->productRepository->createProduct('Product 1', 100);
    }
}
