<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Service\PaymentProcessor;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;

class PaymentProcessorTest extends TestCase
{
    private readonly PaypalPaymentProcessor $paypalProcessor;
    private readonly StripePaymentProcessor $stripeProcessor;
    private readonly PaymentProcessor $paymentProcessor;

    /**
     * @throws Exception
     */
    final protected function setUp(): void
    {
        $this->paypalProcessor = $this->createMock(PaypalPaymentProcessor::class);
        $this->stripeProcessor = $this->createMock(StripePaymentProcessor::class);
        $this->paymentProcessor = new PaymentProcessor($this->paypalProcessor, $this->stripeProcessor);
    }

    /**
     * @throws \Exception
     */
    final public function testProcessPaymentWithPaypal(): void
    {
        $this->paypalProcessor->expects($this->once())->method('pay')->with($this->equalTo(100));
        $this->paymentProcessor->processPayment('paypal', 100);
    }

    /**
     * @throws \Exception
     */
    final public function testProcessPaymentWithStripe(): void
    {
        $this->stripeProcessor->expects($this->once())->method('processPayment')->with($this->equalTo(100));
        $this->paymentProcessor->processPayment('stripe', 100);
    }

    /**
     * @throws \Exception
     */
    final public function testProcessPaymentWithInvalidProcessor(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->paymentProcessor->processPayment('invalid', 100);
    }
}
