<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Exception\InvalidTaxNumberException;
use App\Exception\ProductNotFoundException;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use App\Repository\TaxCountryRepository;

readonly class PriceCalculator
{
    public function __construct(
        private ProductRepository $productRepository,
        private CouponRepository $couponRepository,
        private TaxCountryRepository $taxCountryRepository,
    ) {}

    /**
     * @throws InvalidTaxNumberException|ProductNotFoundException
     */
    final public function calculatePrice(int $productId, string $taxNumber, ?string $couponCode): float
    {
        /** @var Product|null $product */
        $product = $this->productRepository->find($productId);
        if (!$product) {
            throw new ProductNotFoundException('Product not found');
        }

        $basePrice = $product->getPrice();
        $taxRate = $this->getTaxRate($taxNumber);
        $resultPrice = $basePrice - $taxRate;

        if ($couponCode) {
            /** @var Coupon|null $coupon */
            $coupon = $this->couponRepository->findOneBy(['code' => $couponCode]);
            if ($coupon) {
                if ($coupon->getDiscountAmount()) {
                    $resultPrice = $basePrice - $coupon->getDiscountAmount() - $taxRate;
                }

                if ($coupon->getDiscountPercentage()) {
                    $resultPrice = $basePrice - $basePrice * $coupon->getDiscountPercentage() / 100;
                    $resultPrice = round($resultPrice - $resultPrice * $taxRate / 100, 2);
                }
            }
        }

        return $resultPrice;
    }

    /**
     * @throws InvalidTaxNumberException
     */
    private function getTaxRate(string $taxNumber): float
    {
        return match (1) {
            preg_match('/^DE\d{9}$/', $taxNumber) => $this->taxCountryRepository->getTaxByCountry('Germany'),
            preg_match('/^IT\d{11}$/', $taxNumber) => $this->taxCountryRepository->getTaxByCountry('Italy'),
            preg_match('/^FR[A-Z]{2}\d{9}$/', $taxNumber) => $this->taxCountryRepository->getTaxByCountry('France'),
            preg_match('/^GR\d{9}$/', $taxNumber) => $this->taxCountryRepository->getTaxByCountry('Greece'),
            default => throw new InvalidTaxNumberException('Invalid tax number'),
        };
    }
}
