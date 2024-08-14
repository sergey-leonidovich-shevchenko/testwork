<?php

declare(strict_types=1);

namespace App\ArgumentResolver;

use App\Dto\ExchangeRequestDto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ExchangeRequestDtoResolver implements ValueResolverInterface
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    final public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return ExchangeRequestDto::class === $argument->getType();
    }

    final public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $data = $request->toArray();
        $dto = new ExchangeRequestDto(
            $data['fromCurrency'],
            $data['toCurrency'],
            $data['amount']
        );

        $errors = $this->validator->validate($dto);
        if (\count($errors) > 0) {
            throw new ValidationFailedException($errors);
        }

        yield $dto;
    }
}
