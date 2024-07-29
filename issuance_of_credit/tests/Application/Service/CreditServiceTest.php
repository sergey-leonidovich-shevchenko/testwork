<?php

declare(strict_types=1);

namespace App\Tests\Application\Service;

use App\Application\Service\CreditService;
use App\Domain\Entity\Client;
use App\Domain\Entity\CreditProduct;
use PHPUnit\Framework\TestCase;
use Random\RandomException;
use Symfony\Component\Validator\Validation;

class CreditServiceTest extends TestCase
{
    private CreditService $creditService;

    final protected function setUp(): void
    {
        $validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
        $this->creditService = new CreditService($validator);
    }

    /**
     * @throws RandomException
     */
    final public function testCanIssueCredit(): void
    {
        $client = new Client(
            'John',
            'Doe',
            25,
            'Los Angeles',
            'CA',
            '90001',
            '123-45-6789',
            750,
            'john.doe@example.com',
            '555-1234',
            2000
        );

        $this->assertTrue($this->creditService->canIssueCredit($client));
    }

    /**
     * @throws RandomException
     */
    final public function testCannotIssueCreditDueToLowFico(): void
    {
        $client = new Client(
            'John',
            'Doe',
            25,
            'Los Angeles',
            'CA',
            '90001',
            '123-45-6789',
            450,
            'john.doe@example.com',
            '555-1234',
            2000
        );

        $this->assertFalse($this->creditService->canIssueCredit($client));
    }

    /**
     * @throws RandomException
     */
    final public function testCannotIssueCreditDueToRandomNYRestriction(): void
    {
        $client = new Client(
            'John',
            'Doe',
            25,
            'New York',
            'NY',
            '10001',
            '123-45-6789',
            750,
            'john.doe@example.com',
            '555-1234',
            2000
        );

        // Имитация случайного отказа
        mt_srand(1);
        $this->assertFalse($this->creditService->canIssueCredit($client));
    }

    /**
     * @throws RandomException
     */
    final public function testIssueCredit(): void
    {
        $client = new Client(
            'John',
            'Doe',
            25,
            'Los Angeles',
            'CA',
            '90001',
            '123-45-6789',
            750,
            'john.doe@example.com',
            '555-1234',
            2000
        );

        $creditProduct = new CreditProduct('Personal Loan', 36, 5.0, 10000);
        $this->creditService->issueCredit($client, $creditProduct);
        $this->assertSame(16.49, $creditProduct->getInterestRate());
    }
}
