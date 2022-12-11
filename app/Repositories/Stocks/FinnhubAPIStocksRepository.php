<?php

namespace App\Repositories\Stocks;

use App\Models\Collections\StocksCollection;
use App\Models\Stock;

class FinnhubAPIStocksRepository implements StocksRepository
{
    public function getStocks(array $stockSymbols): StocksCollection
    {
        //  /search?q=apple Query text can be symbol, name, isin, or cusip
        //  /quote?symbol=AAPL
        $apiKey = $_ENV["API_KEY"];
        $stocks = new StocksCollection();
        foreach ($stockSymbols as $symbol) {
            $baseUrl = $_ENV["BASE_URL"];
            $endpoint = "/quote";
            $query = "?symbol={$symbol}&token=" . $apiKey;
            $url = curl_init($baseUrl . $endpoint . $query);
            curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
            $request = curl_exec($url);
            $response = json_decode($request);

            $stocks->add(new Stock($symbol, $response->c, $response->c-$response->pc,));
        }
        return $stocks;
    }
}

