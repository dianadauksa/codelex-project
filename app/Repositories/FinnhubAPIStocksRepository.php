<?php

namespace App\Repositories;

use App\Models\Collections\StocksCollection;
use App\Models\Stock;

class FinnhubAPIStocksRepository implements StocksRepository
{
    public function getStocks(array $stockSymbols): StocksCollection
    {
        // /stock/symbol?exchange=US   /quote?symbol=AAPL
        $apiKey = $_ENV["API_KEY"];
        $stocks = [];
        foreach ($stockSymbols as $key => $value) {
            $baseUrl = $_ENV["BASE_URL"];
            $endpoint = "/quote";
            $query = "?symbol={$value}&token=" . $apiKey;
            $ch = curl_init($baseUrl . $endpoint . $query);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            $apiResponse = json_decode($response);

            $stocks[] = new Stock(
                $value,
                $key,
                'USD',
                (float)$apiResponse->c,
                (float)($apiResponse->c-$apiResponse->pc),
            );
        }
        sort($stocks);
        return new StocksCollection($stocks);
    }
}

