<?php

namespace Exchange;

class CurrencyConverter
{
    public function convertCurrency(
        $exchangeRates,
        $sourceCurrency,
        $targetCurrency,
        $amount,
        $recommendedApi
    ): string
    {
        if (isset($exchangeRates[$recommendedApi][$sourceCurrency]) && isset($exchangeRates[$recommendedApi][$targetCurrency])) {
            $sourceToUSD = $amount / $exchangeRates[$recommendedApi][$sourceCurrency];
            return $sourceToUSD * $exchangeRates[$recommendedApi][$targetCurrency];
        } else {
            return "Exchange rate not found for the specified currencies.\n";
        }
    }
}