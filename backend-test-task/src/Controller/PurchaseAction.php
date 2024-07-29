<?php

declare(strict_types=1);

namespace App\Controller;

use App\UseCase\Action\Product\PurchaseUseCase;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

readonly class PurchaseAction
{
    public function __construct(private readonly PurchaseUseCase $purchaseUseCase) {}

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
