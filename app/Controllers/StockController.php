<?php

namespace App\Controllers;

use App\Repositories\FinnhubAPIStocksRepository;
use App\Services\ShowAllStocksService;
use App\View;

class StockController
{
    public function index(): View
    {
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
        $service = new ShowAllStocksService();
        $stocks = $service->execute($stockSymbols);
        return new View("stocks", ["stocks" => $stocks->getStocks()]);
    }
}

