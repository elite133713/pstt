<?php

namespace Pstt\Providers;

use Pstt\Interfaces\CurrencyProviderInterface;

class CurrencyProvider implements CurrencyProviderInterface
{
    private const EXCHANGE_RATE_ENDPOINT = 'https://api.exchangeratesapi.io';

    public function getExchangeRate(string $currency): float
    {
        $content = file_get_contents(self::EXCHANGE_RATE_ENDPOINT . '/latest');

        if ($content === null) {
            throw new \Exception("Can't get exchange rate");
        }

        $content = json_decode($content, true);

        return $content['rates'][$currency] ?? 0;
    }
}

