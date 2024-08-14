<?php

declare(strict_types=1);

namespace App\Service;

use App\Enum\CurrencyEnum;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class ECBDataProvider implements CurrencyDataProviderInterface
{
    public function __construct(private HttpClientInterface $client)
    {
    }

    final public function fetchRates(): array
    {
        $response = $this->client->request('GET', 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml');
        $content = $response->getContent();

        $xml = \simplexml_load_string($content);
        $namespaces = $xml->getNamespaces(true);
        $cube = $xml->xpath('//xmlns:Cube[@currency and @rate]');
        $rates = [CurrencyEnum::EUR->value => 1.0]; // Базовая валюта для ECB - EUR
        foreach ($cube as $rate) {
            $currency = (string) $rate['currency'];
            $value = (float) $rate['rate'];
            $rates[$currency] = $value;
        }

        return $rates;
    }
}
