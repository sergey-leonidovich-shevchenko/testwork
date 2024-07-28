<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class ExistingProduct extends Constraint
{
    public string $message = 'The product with id "{{ value }}" does not exist.';

    public function __construct(string $message = null, array $groups = null)
    {
        parent::__construct(groups: $groups);
        $this->message = $message ?? $this->message;
    }
}
