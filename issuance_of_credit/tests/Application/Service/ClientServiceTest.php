<?php

declare(strict_types=1);

namespace App\Tests\Application\Service;

use App\Application\Service\ClientService;
use App\Domain\Entity\Client;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

class ClientServiceTest extends TestCase
{
    private ClientService $clientService;

    final protected function setUp(): void
    {
        $validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
        $this->clientService = new ClientService($validator);
    }

    final public function testCreateClient(): void
    {
        $client = $this->clientService->createClient(
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

        $this->assertInstanceOf(Client::class, $client);
        $this->assertSame('John', $client->getFirstName());
        $this->assertSame('Doe', $client->getLastName());
    }

    final public function testUpdateClient(): void
    {
        $client = $this->clientService->createClient(
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

        $updatedClient = $this->clientService->updateClient($client, ['firstName' => 'Jane', 'age' => 30]);

        $this->assertSame('Jane', $updatedClient->getFirstName());
        $this->assertSame(30, $updatedClient->getAge());
    }

    final public function testCreateClientWithInvalidData(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->clientService->createClient(
            '',
            'Doe',
            25,
            'Los Angeles',
            'CA',
            '90001',
            '123-45-6789',
            750,
            'invalid-email',
            '555-1234',
            2000
        );
    }
}
