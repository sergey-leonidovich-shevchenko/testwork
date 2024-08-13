<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\ExchangeRequestDto;
use App\Service\ExchangeRateCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CurrencyController extends AbstractController
{
    public function __construct(private readonly ExchangeRateCalculator $exchangeRateCalculator)
    {
    }

    // TODO: Make as invokable controller
    #[Route('/api/exchange', methods: ['POST'])]
    final public function exchange(ExchangeRequestDto $request): JsonResponse
    {
        // TODO: Move to Use Case
        $convertedAmount = $this->exchangeRateCalculator->calculate($request);

        return new JsonResponse(['convertedAmount' => $convertedAmount]);
    }
}
