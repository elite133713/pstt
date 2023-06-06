<?php

use PHPUnit\Framework\TestCase;
use Pstt\CommissionCalculator;
use Pstt\Providers\BinProvider;
use Pstt\Providers\CurrencyProvider;

class CommissionCalculatorTest extends TestCase
{
    public function testCalculateCommissionReturnsNullIfRowIsEmpty()
    {
        $binProviderMock = $this->createMock(BinProvider::class);
        $ratesProviderMock = $this->createMock(CurrencyProvider::class);

        $calculator = new CommissionCalculator('', $binProviderMock, $ratesProviderMock);

        $result = $calculator->calculateCommission();

        $this->assertNull($result);
    }

    public function testCalculateCommissionThrowsExceptionIfBinProviderReturnsFalse()
    {
        $binProviderMock = $this->createMock(BinProvider::class);
        $binProviderMock->method('searchBin')->willReturn(false);

        $ratesProviderMock = $this->createMock(CurrencyProvider::class);

        $calculator = new CommissionCalculator('{"bin":"123456","amount":"100.00","currency":"USD"}', $binProviderMock, $ratesProviderMock);

        $this->expectException(\Exception::class);

        $calculator->calculateCommission();
    }

    public function testCalculateCommissionCalculatesCorrectCommissionForEuCountries()
    {
        $binProviderMock = $this->createMock(BinProvider::class);
        $binProviderMock->method('searchBin')->willReturn('{"country":{"alpha2":"DE"}}');

        $ratesProviderMock = $this->createMock(CurrencyProvider::class);
        $ratesProviderMock->method('getExchangeRate')->willReturn(1.5);

        $calculator = new CommissionCalculator('{"bin":"123456","amount":"100.00","currency":"EUR"}', $binProviderMock, $ratesProviderMock);

        $result = $calculator->calculateCommission();

        $this->assertEquals(1.00, $result);
    }

    public function testExtractDataReturnsCorrectData()
    {
        $binProviderMock = $this->createMock(BinProvider::class);
        $ratesProviderMock = $this->createMock(CurrencyProvider::class);

        $calculator = new CommissionCalculator('{"bin":"123456","amount":"100.00","currency":"USD"}', $binProviderMock, $ratesProviderMock);

        $result = $calculator->extractData();

        $this->assertEquals(['123456', '100.00', 'USD'], $result);
    }

    /**
     * @return void
     * @dataProvider dataProviderCountries
     */
    public function testIsEuReturnsTrueForEuCountries($country, $isEu)
    {
        $binProviderMock = $this->createMock(BinProvider::class);
        $ratesProviderMock = $this->createMock(CurrencyProvider::class);

        $calculator = new CommissionCalculator('', $binProviderMock, $ratesProviderMock);

        $this->assertEquals($isEu, $calculator->isEuCountry($country));
    }

    public function dataProviderCountries()
    {
        return [
            ['AU', false],
            ['US', false],
            ['DK', true],
            ['NL', true],
            ['PO', true],
        ];
    }
}

