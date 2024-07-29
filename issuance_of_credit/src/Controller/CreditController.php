<?php

declare(strict_types=1);

namespace App\Controller;

use App\Application\Service\CreditService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreditController
{
    public function __construct(private readonly CreditService $creditService)
    {}

    public function checkCredit(Request $request, int $clientId): Response
    {
        // Логика проверки возможности выдачи кредита...

        return $this->json(['canIssue' => true]);
    }

    public function issueCredit(Request $request, int $clientId): Response
    {
        // Логика выдачи кредита...

        return $this->json(['status' => 'success']);
    }
}
