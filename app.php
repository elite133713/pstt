<?php

use Pstt\CommissionCalculator;
use Pstt\Providers\BinProvider;
use Pstt\Providers\CurrencyProvider;

require_once 'vendor/autoload.php'; // Include the autoloader

$rows = explode("\n", file_get_contents($argv[1]));

foreach ($rows as $row) {
    try {
        $calculator = new CommissionCalculator($row, new BinProvider(), new CurrencyProvider());
        $commission = $calculator->calculateCommission();
        echo $commission . "\n";
    } catch (Exception $e) {
       // handle the exception
    }
}

