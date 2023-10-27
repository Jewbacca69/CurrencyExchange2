<?php

namespace Exchange;

class Application
{
    private ExchangeRateCollection $exchangeCollection;
    private CurrencyConverter $currencyConverter;


    public function __construct()
    {
        $this->exchangeCollection = new ExchangeRateCollection();
        $this->currencyConverter = new CurrencyConverter();
    }

    public function run($apiConfig, $apiHandlers): void
    {
        $sourceInput = readline("Enter the source currency and amount (e.g., 20 EUR): ");
        $targetCurrency = readline("Enter the target currency (e.g., USD or any other currency shortcode): ");

        list($amount, $source) = sscanf($sourceInput, "%f %s");

        if (!is_numeric($amount) || empty($source) || empty($targetCurrency)) {
            echo "Invalid input. Example input ( 2 EUR )\n";
        } else {

            foreach ($apiHandlers as $apiName => $data) {
                $this->exchangeCollection->addExchangeRate($apiName, $data);
            }

            $recommendedApi = $this->findBestExchange($this->exchangeCollection->getExchangeRates(), $source);
            $value = $this->exchangeCollection->getExchangeRates()[$recommendedApi][$source];

            echo "====================\n";
            echo "Recommended Exchange: " . $this->parseUrl($apiConfig[$recommendedApi]['apiUrl']) . ", Rate : $value\n";
            echo "====================\n";

            foreach ($apiHandlers as $apiName => $data) {
                if ($apiName !== $recommendedApi) {
                    echo "Rate at " . $this->parseUrl($apiConfig[$apiName]['apiUrl']) . " : " . $data[$source] . "\n";
                }
            }
            echo "====================\n";

            $exchangeAmount = $this->currencyConverter->convertCurrency(
                $this->exchangeCollection->getExchangeRates(),
                $source,
                $targetCurrency,
                $amount,
                $recommendedApi
            );

            echo "$amount $source equals " . number_format($exchangeAmount, 2) . " $targetCurrency.\n";
        }
    }

    private function findBestExchange($exchangeRates, $sourceCurrency): string
    {
        $recommendedApi = null;
        $minRate = PHP_FLOAT_MAX;

        foreach ($exchangeRates as $apiName => $data) {
            $exchangeRate = $data[$sourceCurrency];

            if ($exchangeRate < $minRate) {
                $recommendedApi = $apiName;
                $minRate = $exchangeRate;
            }
        }

        return $recommendedApi;
    }

    private function parseUrl($apiUrl): string
    {
        $url = parse_url($apiUrl);
        return $url["host"];
    }
}
