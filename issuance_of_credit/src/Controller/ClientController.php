<?php

declare(strict_types=1);

namespace App\Controller;

use App\Application\Service\ClientService;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ClientController extends AbstractController
{
    private ClientService $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    /**
     * @throws JsonException
     */
    final public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $client = $this->clientService->createClient(
            $data['firstName'],
            $data['lastName'],
            $data['age'],
            $data['city'],
            $data['state'],
            $data['zip'],
            $data['ssn'],
            $data['fico'],
            $data['email'],
            $data['phone'],
            $data['monthlyIncome']
        );

        return $this->json($client);
    }

    final public function update(Request $request, int $clientId): Response
    {
        // Логика обновления клиента...

        return $this->json(['status' => 'success']);
    }
}
