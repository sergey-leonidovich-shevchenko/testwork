<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Coupon;
use Doctrine\Persistence\ManagerRegistry;

class CouponRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Coupon::class);
    }

    final public function createDiscountAmountCoupon(string $code, float $discountAmount): Coupon
    {
        $coupon = (new Coupon())->setCode($code)->setDiscountAmount($discountAmount);
        $this->save($coupon);

        return $coupon;
    }

    final public function createDiscountPercentageCoupon(string $code, float $discountPercentage): Coupon
    {
        $coupon = (new Coupon())->setCode($code)->setDiscountPercentage($discountPercentage);
        $this->save($coupon);

        return $coupon;
    }
}
