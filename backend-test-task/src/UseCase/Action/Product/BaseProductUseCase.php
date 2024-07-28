<?php

declare(strict_types=1);

namespace App\UseCase\Action\Product;

use App\Dto\ProductRequestDto;
use App\Exception\ValidationFailedException;
use JsonException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BaseProductUseCase
{
    public function __construct(protected ValidatorInterface $validator)
    {}

    /**
     * @throws JsonException|ValidationFailedException
     */
    final protected function getValidatedData(Request $request): ProductRequestDto
    {
        $data = json_decode($request->getContent(), false, 512, JSON_THROW_ON_ERROR);
        $productRequest = new ProductRequestDto();
        $productRequest->product = $data->product;
        $productRequest->taxNumber = $data->taxNumber;
        $productRequest->couponCode = $data->couponCode ?? null;
        $productRequest->paymentProcessor = $data->paymentProcessor ?? null;

        $errors = $this->validateData($productRequest);
        if ($errors->count() > 0) {
            throw new ValidationFailedException($errors);
        }

        return $productRequest;
    }

    private function validateData(ProductRequestDto $data): ConstraintViolationListInterface
    {
        return $this->validator->validate($data);
    }
}
