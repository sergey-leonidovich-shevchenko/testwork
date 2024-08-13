<?php

declare(strict_types=1);

namespace Tests\Functional\Controller;

use App\Enum\CurrencyEnum;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CurrencyControllerTest extends WebTestCase
{
    /**
     * @throws \JsonException
     */
    final public function testExchange(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/exchange', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'fromCurrency' => CurrencyEnum::USD->value,
            'toCurrency' => CurrencyEnum::GBP->value,
            'amount' => 100,
        ], JSON_THROW_ON_ERROR));

        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->assertArrayHasKey('convertedAmount', $data);
        $this->assertEquals(66.67, round($data['convertedAmount'], 2)); // Проверка на округленное значение
    }
}
