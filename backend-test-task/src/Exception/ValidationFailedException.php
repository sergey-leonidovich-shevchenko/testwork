<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationFailedException extends \Exception
{
    private ConstraintViolationListInterface $errors;

    public function __construct(ConstraintViolationListInterface $errors)
    {
        parent::__construct('Validation error');
        $this->errors = $errors;
    }

    final public function getResponse(): ConstraintViolationListInterface
    {
        return $this->errors;
    }
}
