<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\PaymentProcessor;
use App\Service\PriceCalculator;
use Exception;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    public function __construct(
        private readonly PriceCalculator $priceCalculator,
        private readonly PaymentProcessor $paymentProcessor
    ) {}

    /**
     * @throws JsonException
     */
    #[Route('/calculate-price', name: 'calculate_price', methods: ['POST'])]
    final public function calculatePrice(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        try {
            $price = $this->priceCalculator->calculatePrice($data['product'], $data['taxNumber'], $data['couponCode'] ?? null);
            return new JsonResponse(['price' => $price], 200);
        } catch (Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @throws JsonException
     */
    #[Route('/purchase', name: 'purchase', methods: ['POST'])]
    final public function purchase(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        try {
            $price = $this->priceCalculator->calculatePrice($data['product'], $data['taxNumber'], $data['couponCode'] ?? null);
            $this->paymentProcessor->processPayment($data['paymentProcessor'], $price);
            return new JsonResponse(['status' => 'success'], 200);
        } catch (Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }
}
