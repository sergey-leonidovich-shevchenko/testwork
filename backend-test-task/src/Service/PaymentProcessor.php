<?php

declare(strict_types=1);

namespace App\Service;

use Exception;
use InvalidArgumentException;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;

readonly class PaymentProcessor
{
    public function __construct(
        private PaypalPaymentProcessor $paypalProcessor,
        private StripePaymentProcessor $stripeProcessor
    ) {}

    /**
     * @throws Exception
     */
    final public function processPayment(string $processor, float $amount): void
    {
        match ($processor) {
            'paypal' => $this->paypalProcessor->pay((int)$amount),
            'stripe' => $this->stripeProcessor->processPayment((int)$amount),
            default => throw new InvalidArgumentException('Invalid payment processor'),
        };
    }
}
