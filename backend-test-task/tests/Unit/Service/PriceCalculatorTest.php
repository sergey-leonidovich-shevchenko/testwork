<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Exception\InvalidTaxNumberException;
use App\Exception\ProductNotFoundException;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use App\Repository\TaxCountryRepository;
use App\Service\PriceCalculator;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class PriceCalculatorTest extends TestCase
{
    private readonly ProductRepository $productRepository;
    private readonly CouponRepository $couponRepository;
    private readonly TaxCountryRepository $taxCountryRepository;
    private readonly PriceCalculator $priceCalculator;

    /**
     * @throws Exception
     */
    final protected function setUp(): void
    {
        $this->productRepository = $this->createMock(ProductRepository::class);
        $this->couponRepository = $this->createMock(CouponRepository::class);
        $this->taxCountryRepository = $this->createMock(TaxCountryRepository::class);
        $this->priceCalculator = new PriceCalculator(
            $this->productRepository,
            $this->couponRepository,
            $this->taxCountryRepository,
        );
    }

    /**
     * @throws ProductNotFoundException|InvalidTaxNumberException
     */
    final public function testCalculatePriceWithAmountCouponForGermany(): void
    {
        $product = new Product();
        $product->setPrice(100);

        $coupon = new Coupon();
        $coupon->setDiscountAmount(15);

        $this->productRepository->method('find')->willReturn($product);
        $this->couponRepository->method('findOneBy')->willReturn($coupon);
        $this->taxCountryRepository->method('getTaxByCountry')->willReturn(19.0);

        $price = $this->priceCalculator->calculatePrice(1, 'DE123456789', 'D15');
        $this->assertEquals(66, $price);
    }

    /**
     * @throws ProductNotFoundException|InvalidTaxNumberException
     */
    final public function testCalculatePriceWithPercentageCouponForGermany(): void
    {
        $product = new Product();
        $product->setPrice(100);

        $coupon = new Coupon();
        $coupon->setDiscountPercentage(15);

        $this->productRepository->method('find')->willReturn($product);
        $this->couponRepository->method('findOneBy')->willReturn($coupon);
        $this->taxCountryRepository->method('getTaxByCountry')->willReturn(19.0);

        $price = $this->priceCalculator->calculatePrice(1, 'DE123456789', 'DP15');
        $this->assertEquals(68.85, $price);
    }

    /**
     * @throws ProductNotFoundException|InvalidTaxNumberException
     */
    final public function testCalculatePriceWithAmountCouponForItaly(): void
    {
        $product = new Product();
        $product->setPrice(100);

        $coupon = new Coupon();
        $coupon->setDiscountAmount(15);

        $this->productRepository->method('find')->willReturn($product);
        $this->couponRepository->method('findOneBy')->willReturn($coupon);
        $this->taxCountryRepository->method('getTaxByCountry')->willReturn(22.0);

        $price = $this->priceCalculator->calculatePrice(1, 'IT12345678901', 'D15');
        $this->assertEquals(63, $price);
    }

    /**
     * @throws ProductNotFoundException|InvalidTaxNumberException
     */
    final public function testCalculatePriceWithPercentageCouponForItaly(): void
    {
        $product = new Product();
        $product->setPrice(100);

        $coupon = new Coupon();
        $coupon->setDiscountPercentage(15);

        $this->productRepository->method('find')->willReturn($product);
        $this->couponRepository->method('findOneBy')->willReturn($coupon);
        $this->taxCountryRepository->method('getTaxByCountry')->willReturn(22.0);

        $price = $this->priceCalculator->calculatePrice(1, 'IT12345678901', 'DP15');
        $this->assertEquals(66.3, $price);
    }

    /**
     * @throws ProductNotFoundException|InvalidTaxNumberException
     */
    final public function testCalculatePriceWithAmountCouponForFrance(): void
    {
        $product = new Product();
        $product->setPrice(100);

        $coupon = new Coupon();
        $coupon->setDiscountAmount(15);

        $this->productRepository->method('find')->willReturn($product);
        $this->couponRepository->method('findOneBy')->willReturn($coupon);
        $this->taxCountryRepository->method('getTaxByCountry')->willReturn(20.0);

        $price = $this->priceCalculator->calculatePrice(1, 'FRXX123456789', 'D15');
        $this->assertEquals(65, $price);
    }

    /**
     * @throws ProductNotFoundException|InvalidTaxNumberException
     */
    final public function testCalculatePriceWithPercentageCouponForFrance(): void
    {
        $product = new Product();
        $product->setPrice(100);

        $coupon = new Coupon();
        $coupon->setDiscountPercentage(15);

        $this->productRepository->method('find')->willReturn($product);
        $this->couponRepository->method('findOneBy')->willReturn($coupon);
        $this->taxCountryRepository->method('getTaxByCountry')->willReturn(20.0);

        $price = $this->priceCalculator->calculatePrice(1, 'FRXX123456789', 'DP15');
        $this->assertEquals(68, $price);
    }

    /**
     * @throws ProductNotFoundException|InvalidTaxNumberException
     */
    final public function testCalculatePriceWithAmountCouponForGreece(): void
    {
        $product = new Product();
        $product->setPrice(100);

        $coupon = new Coupon();
        $coupon->setDiscountAmount(15);

        $this->productRepository->method('find')->willReturn($product);
        $this->couponRepository->method('findOneBy')->willReturn($coupon);
        $this->taxCountryRepository->method('getTaxByCountry')->willReturn(24.0);

        $price = $this->priceCalculator->calculatePrice(1, 'GR123456789', 'D15');
        $this->assertEquals(61, $price);
    }

    /**
     * @throws ProductNotFoundException|InvalidTaxNumberException
     */
    final public function testCalculatePriceWithPercentageCouponForGreece(): void
    {
        $product = new Product();
        $product->setPrice(100);

        $coupon = new Coupon();
        $coupon->setDiscountPercentage(15);

        $this->productRepository->method('find')->willReturn($product);
        $this->couponRepository->method('findOneBy')->willReturn($coupon);
        $this->taxCountryRepository->method('getTaxByCountry')->willReturn(24.0);

        $price = $this->priceCalculator->calculatePrice(1, 'GR123456789', 'DP15');
        $this->assertEquals(64.6, $price);
    }

    /**
     * @throws InvalidTaxNumberException
     */
    final public function testInvalidProductException(): void
    {
        $this->expectException(ProductNotFoundException::class);
        $this->priceCalculator->calculatePrice(1, 'INVALID123', null);
    }

    /**
     * @throws ProductNotFoundException
     */
    final public function testCalculatePriceWithInvalidTaxNumber(): void
    {
        $product = new Product();
        $product->setPrice(100);
        $this->productRepository->method('find')->willReturn($product);

        $this->expectException(InvalidTaxNumberException::class);
        $this->priceCalculator->calculatePrice(1, 'INVALID123', null);
    }
}
