<?php

namespace App\Repositories;

use App\Models\Collections\StocksCollection;
use App\Models\Stock;

class FinnhubAPIStocksRepository implements StocksRepository
{
    public function getStocks(array $stockSymbols): StocksCollection
    {
        //  /search?q=apple Query text can be symbol, name, isin, or cusip
        //  /quote?symbol=AAPL
        $apiKey = $_ENV["API_KEY"];
        $stocks = [];
        foreach ($stockSymbols as $symbol) {
            $baseUrl = $_ENV["BASE_URL"];
            $endpoint = "/quote";
            $query = "?symbol={$symbol}&token=" . $apiKey;
            $ch = curl_init($baseUrl . $endpoint . $query);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            $apiResponse = json_decode($response);

            $stocks[] = new Stock(
                $symbol,
                (float)$apiResponse->c,
                (float)($apiResponse->c-$apiResponse->pc),
            );
        }
        sort($stocks);
        return new StocksCollection($stocks);
    }
}

