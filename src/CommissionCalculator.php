<?php

namespace Pstt;

use Pstt\Interfaces\CommissionCalculatorInterface;
use Pstt\Providers\BinProvider;
use Pstt\Providers\CurrencyProvider;

class CommissionCalculator implements CommissionCalculatorInterface
{
    private $row;

    private BinProvider $binProvider;
    private CurrencyProvider $ratesProvider;

    private const EU_COMMISSION_RATE = 0.01;
    private const NON_EU_COMMISSION_RATE = 0.02;

    private const EUR_CURRENCY = 'EUR';

    public function __construct($row, BinProvider $binProvider, CurrencyProvider $ratesProvider)
    {
        $this->row = $row;

        $this->binProvider = $binProvider;
        $this->ratesProvider = $ratesProvider;
    }

    public function calculateCommission(): float|null
    {
        if (empty($this->row)) {
            return null;
        }

        [$bin, $amount, $currency] = $this->extractData();

        $binResults = $this->binProvider->searchBin($bin);

        if (!$binResults) {
            throw new \Exception("Can't get data");
        }

        $binData = json_decode($binResults);
        $isEu = $this->isEuCountry($binData->country->alpha2);

        $rate = $this->ratesProvider->getExchangeRate($currency);

        if ($currency === self::EUR_CURRENCY || $rate == 0) {
            $amountFixed = $amount;
        } else {
            $amountFixed = $amount / $rate;
        }

        $commission = $amountFixed * ($isEu ? self::EU_COMMISSION_RATE : self::NON_EU_COMMISSION_RATE);

        return $this->roundCommission($commission);
    }

    public function extractData(): array
    {
        $params = json_decode($this->row, true, 512, JSON_THROW_ON_ERROR);

        return [$params['bin'], $params['amount'], $params['currency']];
    }

    public function isEuCountry(string $code): bool
    {
        $euCountries = [
            'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GR', 'HR',
            'HU', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PO', 'PT', 'RO', 'SE', 'SI', 'SK',
        ];

        return in_array($code, $euCountries);
    }

    private function roundCommission(float $commission): float
    {
        return ceil($commission * 100) / 100;
    }
}

