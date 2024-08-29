<?php

declare(strict_types=1);

namespace Tests\Unit\Service;

use App\Enum\CurrencyEnum;
use App\Service\ECBDataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ECBDataProviderTest extends TestCase
{
    final public function testFetchRates(): void
    {
        $httpClient = $this->createMock(HttpClientInterface::class);

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getContent')->willReturn('<gesmes:Envelope xmlns:gesmes="http://www.gesmes.org/xml/2002-08-01" xmlns="http://www.ecb.int/vocabulary/2002-08-01/eurofxref">
            <Cube>
                <Cube time="2024-08-13">
                    <Cube currency="USD" rate="1.2345"/>
                    <Cube currency="GBP" rate="0.9876"/>
                </Cube>
            </Cube>
        </gesmes:Envelope>');
        $httpClient->method('request')->willReturn($response);

        $provider = new ECBDataProvider($httpClient);
        $rates = $provider->fetchRates();
        $this->assertIsArray($rates);
        $this->assertArrayHasKey(CurrencyEnum::USD->value, $rates);
        $this->assertEquals(1.2345, $rates[CurrencyEnum::USD->value]);
        $this->assertEquals(0.9876, $rates[CurrencyEnum::GBP->value]);
        $this->assertEquals(1.0, $rates[CurrencyEnum::EUR->value]); // Базовая валюта для ECB - EUR
    }
}
