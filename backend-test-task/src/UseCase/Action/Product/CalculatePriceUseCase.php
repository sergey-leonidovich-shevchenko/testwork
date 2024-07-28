<?php

declare(strict_types=1);

namespace App\UseCase\Action\Product;

use App\Exception\InvalidTaxNumberException;
use App\Exception\ProductNotFoundException;
use App\Exception\ValidationFailedException;
use App\Service\PriceCalculator;
use JsonException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CalculatePriceUseCase extends BaseProductUseCase
{
    public function __construct(
        private readonly PriceCalculator $priceCalculator,
        protected ValidatorInterface $validator
    ) {
        parent::__construct($validator);
    }

    /**
     * @throws InvalidTaxNumberException|ProductNotFoundException|ValidationFailedException|JsonException
     */
    final public function execute(Request $request): float
    {
        $productRequestDto = $this->getValidatedData($request);

        return $this->priceCalculator->calculatePrice(
            $productRequestDto->product,
            $productRequestDto->taxNumber,
            $productRequestDto->couponCode ?? null
        );
    }
}
