<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;

class ProductRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    final public function createProduct(string $name, float $price): Product
    {
        $product = (new Product())->setName($name)->setPrice($price);
        $this->save($product);

        return $product;
    }
}
