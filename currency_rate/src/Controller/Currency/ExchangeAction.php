<?php

declare(strict_types=1);

namespace App\Controller\Currency;

use App\Dto\ExchangeRequestDto;
use App\UseCase\Action\CurrencyExchangeUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ExchangeAction extends AbstractController
{
    public function __construct(
        private readonly CurrencyExchangeUseCase $currencyExchangeUseCase,
    ) {
    }

    #[Route('/api/exchange', methods: ['POST'])]
    final public function __invoke(ExchangeRequestDto $request): JsonResponse
    {
        $convertedAmount = $this->currencyExchangeUseCase->execute($request);

        return new JsonResponse(['convertedAmount' => $convertedAmount]);
    }
}
