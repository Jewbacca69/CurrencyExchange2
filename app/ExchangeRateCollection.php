<?php

namespace Exchange;

class ExchangeRateCollection
{
    private array $exchangeRates = [];

    public function addExchangeRate($apiName, $exchangeRateData)
    {
        $this->exchangeRates[$apiName] = $exchangeRateData;
    }

    public function getExchangeRates(): array
    {
        return $this->exchangeRates;
    }
}