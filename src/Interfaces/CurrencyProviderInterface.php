<?php

namespace Pstt\Interfaces;

interface CurrencyProviderInterface
{
    public function getExchangeRate(string $currency): float;
}
