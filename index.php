<?php

require_once "vendor/autoload.php";


use Exchange\ApiHandler;
use Exchange\Application;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apiConfig = [
    "FastForex" => [
        "apiUrl" => "https://api.fastforex.io/fetch-all?from=EUR&api_key=",
        "apiKey" => $_ENV['FASTFOREX_API_KEY'],
        "dataKey" => "results"
    ],
    "ExchangeRatesApi" => [
        "apiUrl" => "http://api.exchangeratesapi.io/v1/latest?access_key=",
        "apiKey" => $_ENV['EXCHANGERATESAPI_KEY'],
        "dataKey" => "rates"
    ],

    // GARBAGE! Pārāk maz currency.. apps nestrādā kā vajag :(
    /*  "FreeCurrencyApi" => [
          "apiUrl" => "https://api.freecurrencyapi.com/v1/latest?base_currency=EUR&apikey=",
          "apiKey" => $_ENV['FREECURRENCYAPI_KEY'],
          "dataKey" => "data"
      ] */
];

$apiHandlers = [];
foreach ($apiConfig as $apiName => $config) {
    $apiHandlers[$apiName] = (new ApiHandler($config['apiUrl'], $config['apiKey'], $config['dataKey']))->fetchData();
}

$app = new Application();
$app->run($apiConfig, $apiHandlers);
