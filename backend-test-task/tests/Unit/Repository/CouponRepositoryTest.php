<?php

declare(strict_types=1);

namespace App\Tests\Unit\Repository;

use App\Entity\Coupon;
use App\Repository\CouponRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CouponRepositoryTest extends KernelTestCase
{
    private readonly CouponRepository $couponRepository;

    final public function setUp(): void
    {
        parent::setUp();
        $this->couponRepository = static::getContainer()->get(CouponRepository::class);
    }

    final public function testCreateDiscountAmountCoupon(): void
    {
        $coupon = $this->couponRepository->createDiscountAmountCoupon('P15', 15);
        $couponFromDB = $this->couponRepository->find($coupon->getId());

        $this->assertInstanceOf(Coupon::class, $couponFromDB);
        $this->assertEquals($coupon->getCode(), $couponFromDB->getCode());
        $this->assertEquals($coupon->getDiscountAmount(), $couponFromDB->getDiscountAmount());
    }

    final public function testCreateDiscountPercentageCoupon(): void
    {
        $coupon = $this->couponRepository->createDiscountPercentageCoupon('DP15', 15);
        $couponFromDB = $this->couponRepository->find($coupon->getId());

        $this->assertInstanceOf(Coupon::class, $couponFromDB);
        $this->assertEquals($coupon->getCode(), $couponFromDB->getCode());
        $this->assertEquals($coupon->getDiscountPercentage(), $couponFromDB->getDiscountPercentage());
    }
}
