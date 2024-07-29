<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domain\Entity\Client;
use App\Domain\Entity\CreditProduct;
use Random\RandomException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreditService
{
    public function __construct(private readonly ValidatorInterface $validator)
    {}

    /**
     * @throws RandomException
     */
    final public function canIssueCredit(Client $client): bool
    {
        // TODO: use constant
        if ($client->getFico() <= 500 || $client->getMonthlyIncome() < 1000 || $client->getAge() < 18 || $client->getAge() > 60) {
            return false;
        }

        if (!in_array($client->getState(), ['CA', 'NY', 'NV'])) {
            return false;
        }

        if ($client->getState() === 'NY' && random_int(0, 1) === 0) {
            return false;
        }

        return true;
    }

    /**
     * @throws RandomException
     */
    final public function issueCredit(Client $client, CreditProduct $creditProduct): void
    {
        if (!$this->canIssueCredit($client)) {
            throw new \RuntimeException('Credit cannot be issued');
        }

        // Валидация кредитного продукта
        $errors = $this->validator->validate($creditProduct);
        if (count($errors) > 0) {
            throw new \InvalidArgumentException((string) $errors);
        }

        if ($client->getState() === 'CA') {
            $creditProduct->setInterestRate($creditProduct->getInterestRate() + 11.49);
        }

        // Уведомление клиента (Email, SMS)...
    }
}
