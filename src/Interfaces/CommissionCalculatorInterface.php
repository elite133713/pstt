<?php

namespace Pstt\Interfaces;

interface CommissionCalculatorInterface
{
    public function calculateCommission(): float|null;

    public function extractData(): array;

    public function isEuCountry(string $code): bool;
}
