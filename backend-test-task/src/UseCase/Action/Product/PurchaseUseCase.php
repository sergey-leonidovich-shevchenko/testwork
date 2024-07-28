<?php

declare(strict_types=1);

namespace App\UseCase\Action\Product;

use App\Exception\InvalidTaxNumberException;
use App\Exception\ProductNotFoundException;
use App\Exception\ValidationFailedException;
use App\Service\PaymentProcessor;
use App\Service\PriceCalculator;
use Exception;
use JsonException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PurchaseUseCase extends BaseProductUseCase
{
    public function __construct(
        private readonly PriceCalculator $priceCalculator,
        private readonly PaymentProcessor $paymentProcessor,
        protected ValidatorInterface $validator
    ) {
        parent::__construct($validator);
    }

    /**
     * @throws ProductNotFoundException|InvalidTaxNumberException|ValidationFailedException|JsonException|Exception
     */
    final public function execute(Request $request): void
    {
        $productRequestDto = $this->getValidatedData($request);
        $price = $this->priceCalculator->calculatePrice(
            $productRequestDto->product,
            $productRequestDto->taxNumber,
            $productRequestDto->couponCode ?? null
        );
        $this->paymentProcessor->processPayment($productRequestDto->paymentProcessor, $price);
    }
}
