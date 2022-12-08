<?php

namespace App\Repositories;

use App\Models\Collections\StocksCollection;
use App\Models\Stock;

class APIStocksRepository implements StocksRepository
{
    public function getStocks(): StocksCollection
    {
        // /stock/symbol?exchange=US   /quote?symbol=AAPL
        $apiKey = $_ENV["API_KEY"];
        $stockSymbols = [
            'Apple Inc' => 'AAPL',
            'Alpjabet Inc' =>'GOOG',
            'Microsoft Corp' => 'MSFT',
            'Amazon.com Inc' => 'AMZN',
            'Meta Platforms Inc' => 'META',
            'Intel Corporation' => 'INTC',
            'Tesla Inc' => 'TSLA',
            'Oracle Corporation' => 'ORCL',
            'IBM Common Stock' => 'IBM',
            'HP Inc' => 'HPQ',
            'Sony Group Corp' => 'SONY',
            'NVIDIA Corp' => 'NVDA'];
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
        return new StocksCollection($stocks);
    }
}

