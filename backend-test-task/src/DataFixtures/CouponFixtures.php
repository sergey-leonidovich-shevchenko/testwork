<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Repository\CouponRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CouponFixtures extends Fixture
{
    public function __construct(private readonly CouponRepository $couponRepository)
    {}

    final public function load(ObjectManager $manager): void
    {
        $this->couponRepository->createDiscountAmountCoupon('D15', 15);
        $this->couponRepository->createDiscountPercentageCoupon('DP15', 15);
    }
}
