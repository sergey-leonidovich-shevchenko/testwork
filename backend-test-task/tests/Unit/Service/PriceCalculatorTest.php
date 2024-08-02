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
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
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

    final public static function calculatePriceDataProvider(): Generator
    {
        yield 'Amount coupon for Germany' => [
            'price' => 100,
            'discountAmount' => 15,
            'discountPercentage' => null,
            'taxCountry' => 19.0,
            'productId' => 1,
            'taxNumber' => 'DE123456789',
            'couponCode' => 'D15',
            'returnPrice' => 66,
        ];

        yield 'Percentage coupon for Germany' => [
            'price' => 100,
            'discountAmount' => null,
            'discountPercentage' => 15,
            'taxCountry' => 19.0,
            'productId' => 1,
            'taxNumber' => 'DE123456789',
            'couponCode' => 'DP15',
            'returnPrice' => 68.85,
        ];

        yield 'Amount coupon for Italy' => [
            'price' => 100,
            'discountAmount' => 15,
            'discountPercentage' => null,
            'taxCountry' => 22.0,
            'productId' => 1,
            'taxNumber' => 'IT12345678901',
            'couponCode' => 'D15',
            'returnPrice' => 63,
        ];

        yield 'Percentage coupon for Italy' => [
            'price' => 100,
            'discountAmount' => null,
            'discountPercentage' => 15,
            'taxCountry' => 22.0,
            'productId' => 1,
            'taxNumber' => 'IT12345678901',
            'couponCode' => 'DP15',
            'returnPrice' => 66.3,
        ];

        yield 'Amount coupon for France' => [
            'price' => 100,
            'discountAmount' => 15,
            'discountPercentage' => null,
            'taxCountry' => 20.0,
            'productId' => 1,
            'taxNumber' => 'FRXX123456789',
            'couponCode' => 'D15',
            'returnPrice' => 65,
        ];

        yield 'Percentage coupon for France' => [
            'price' => 100,
            'discountAmount' => null,
            'discountPercentage' => 15,
            'taxCountry' => 20.0,
            'productId' => 1,
            'taxNumber' => 'FRXX123456789',
            'couponCode' => 'DP15',
            'returnPrice' => 68,
        ];

        yield 'Amount coupon for Greece' => [
            'price' => 100,
            'discountAmount' => 15,
            'discountPercentage' => null,
            'taxCountry' => 24.0,
            'productId' => 1,
            'taxNumber' => 'GR123456789',
            'couponCode' => 'D15',
            'returnPrice' => 61,
        ];

        yield 'Percentage coupon for Greece' => [
            'price' => 100,
            'discountAmount' => null,
            'discountPercentage' => 15,
            'taxCountry' => 24.0,
            'productId' => 1,
            'taxNumber' => 'GR123456789',
            'couponCode' => 'DP15',
            'returnPrice' => 64.6,
        ];
    }

    /**
     * @throws ProductNotFoundException|InvalidTaxNumberException
     */
    #[DataProvider('calculatePriceDataProvider')]
    final public function testCalculatePrice(
        float|int $price,
        float|int|null $discountAmount,
        float|int|null $discountPercentage,
        float|int $taxCountry,
        int $productId,
        string $taxNumber,
        string $couponCode,
        float|int $returnPrice,
    ): void {
        $product = new Product();
        $product->setPrice($price);

        $coupon = new Coupon();
        $discountAmount && $coupon->setDiscountAmount($discountAmount);
        $discountPercentage && $coupon->setDiscountPercentage($discountPercentage);

        $this->productRepository->method('find')->willReturn($product);
        $this->couponRepository->method('findOneBy')->willReturn($coupon);
        $this->taxCountryRepository->method('getTaxByCountry')->willReturn($taxCountry);

        $price = $this->priceCalculator->calculatePrice($productId, $taxNumber, $couponCode);
        $this->assertEquals($returnPrice, $price);
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
