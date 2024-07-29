<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Entity\Client;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ClientService
{
    public function __construct(private readonly ValidatorInterface $validator)
    {}

    final public function createClient(
        string $firstName,
        string $lastName,
        int $age,
        string $city,
        string $state,
        string $zip,
        string $ssn,
        int $fico,
        string $email,
        string $phone,
        float $monthlyIncome
    ): Client {
        $client = new Client(
            $firstName,
            $lastName,
            $age,
            $city,
            $state,
            $zip,
            $ssn,
            $fico,
            $email,
            $phone,
            $monthlyIncome
        );

        $errors = $this->validator->validate($client);
        if (count($errors) > 0) {
            throw new \InvalidArgumentException((string) $errors);
        }

        return $client;
    }

    final public function updateClient(Client $client, array $data): Client
    {
        if (isset($data['firstName'])) {
            $client->setFirstName($data['firstName']);
        }
        if (isset($data['lastName'])) {
            $client->setLastName($data['lastName']);
        }
        if (isset($data['age'])) {
            $client->setAge($data['age']);
        }
        if (isset($data['city'])) {
            $client->setCity($data['city']);
        }
        if (isset($data['state'])) {
            $client->setState($data['state']);
        }
        if (isset($data['zip'])) {
            $client->setZip($data['zip']);
        }
        if (isset($data['ssn'])) {
            $client->setSsn($data['ssn']);
        }
        if (isset($data['fico'])) {
            $client->setFico($data['fico']);
        }
        if (isset($data['email'])) {
            $client->setEmail($data['email']);
        }
        if (isset($data['phone'])) {
            $client->setPhone($data['phone']);
        }
        if (isset($data['monthlyIncome'])) {
            $client->setMonthlyIncome($data['monthlyIncome']);
        }

        $errors = $this->validator->validate($client);
        if (count($errors) > 0) {
            throw new \InvalidArgumentException((string) $errors);
        }

        return $client;
    }
}
