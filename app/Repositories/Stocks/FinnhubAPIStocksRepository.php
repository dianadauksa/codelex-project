<?php

namespace App\Repositories\Stocks;

use App\Models\Collections\StocksCollection;
use App\Models\Stock;
use GuzzleHttp\Client;

class FinnhubAPIStocksRepository implements StocksRepository
{
    public function getAll(array $stockSymbols): StocksCollection
    {
        //  /stock/profile2?symbol=AAPL returns company data
        //  /quote?symbol=AAPL
        $apiKey = $_ENV["API_KEY"];
        $baseUrl = $_ENV["BASE_URL"];
        $endPoint = "/quote?symbol=";

        $client = new Client();
        $stocks = new StocksCollection();

        foreach ($stockSymbols as $symbol) {
            $query = "{$symbol}&token=" . $apiKey;
            $quoteUrl = $baseUrl . $endPoint . $query;
            $quoteResponse = $client->request('GET', $quoteUrl);
            $quoteData = json_decode($quoteResponse->getBody()->getContents());

            $stocks->add(new Stock(
                $symbol,
                $quoteData->c,
                $quoteData->c-$quoteData->pc));
        }
        return $stocks;
    }
}

