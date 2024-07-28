<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProductControllerTest extends WebTestCase
{
    private readonly KernelBrowser $client;
    private readonly ProductRepository $productRepository;
    private readonly CouponRepository $couponRepository;

    final protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();

        $this->productRepository = static::getContainer()->get(ProductRepository::class);
        $this->couponRepository = static::getContainer()->get(CouponRepository::class);
    }

    /**
     * @throws \JsonException
     */
    final public function testCalculatePrice(): void
    {
        $product = $this->productRepository->createProduct('Product 1', 100);
        $coupon = $this->couponRepository->createDiscountAmountCoupon('D15', 15);

        $this->client->request('POST', '/calculate-price', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'product' => $product->getId(),
            'taxNumber' => 'DE123456789',
            'couponCode' => $coupon->getCode(),
        ], JSON_THROW_ON_ERROR));

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    /**
     * @throws \JsonException
     */
    final public function testCalculatePriceWithInvalidData(): void
    {
        $coupon = $this->couponRepository->createDiscountAmountCoupon('D15', 15);

        $this->client->request('POST', '/calculate-price', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'product' => 999,
            'taxNumber' => 'INVALID',
            'couponCode' => $coupon->getCode(),
        ], JSON_THROW_ON_ERROR));

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    /**
     * @throws \JsonException
     */
    final public function testPurchase(): void
    {
        $product = $this->productRepository->createProduct('Product 1', 100);
        $coupon = $this->couponRepository->createDiscountAmountCoupon('D15', 15);

        $this->client->request('POST', '/purchase', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'product' => $product->getId(),
            'taxNumber' => 'IT12345678900',
            'couponCode' => $coupon->getCode(),
            'paymentProcessor' => 'paypal'
        ], JSON_THROW_ON_ERROR));

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    /**
     * @throws \JsonException
     */
    final public function testPurchaseWithInvalidData(): void
    {
        $product = $this->productRepository->createProduct('Product 1', 100);
        $coupon = $this->couponRepository->createDiscountAmountCoupon('D15', 15);

        $this->client->request('POST', '/purchase', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'product' => $product->getId(),
            'taxNumber' => 'IT12345678900',
            'couponCode' => $coupon->getCode(),
            'paymentProcessor' => 'invalid'
        ], JSON_THROW_ON_ERROR));

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }
}
