<?php

declare(strict_types=1);

namespace App\Controller;

use App\Exception\InvalidTaxNumberException;
use App\Exception\ProductNotFoundException;
use App\Exception\ValidationFailedException;
use App\UseCase\Action\Product\CalculatePriceUseCase;
use App\UseCase\Action\Product\PurchaseUseCase;
use Exception;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    public function __construct(
        private readonly CalculatePriceUseCase $calculatePriceUseCase,
        private readonly PurchaseUseCase $purchaseUseCase,
    ) {}

    /**
     * @throws JsonException
     */
    #[Route('/calculate-price', name: 'product.calculate_price', methods: ['POST'])]
    final public function calculatePrice(Request $request): JsonResponse
    {
        try {
            $price = $this->calculatePriceUseCase->execute($request);
            return new JsonResponse(['price' => $price], Response::HTTP_OK);
        } catch (InvalidTaxNumberException|ProductNotFoundException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (ValidationFailedException $e) {
            return $e->getResponse();
        }
    }

    #[Route('/purchase', name: 'product.purchase', methods: ['POST'])]
    final public function purchase(Request $request): JsonResponse
    {
        try {
            $this->purchaseUseCase->execute($request);
            return new JsonResponse(['status' => 'success'], Response::HTTP_OK);
        } catch (Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
