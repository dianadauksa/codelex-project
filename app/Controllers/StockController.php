<?php

namespace App\Controllers;

use App\Services\ShowAllStocksService;
use App\View;

class StockController
{
    public function index(): View
    {
        $stockSymbols = ['AAPL', 'GOOG', 'MSFT', 'AMZN', 'META', 'INTC', 'TSLA', 'ORCL', 'IBM', 'HPQ','SONY', 'NVDA'];
        $service = new ShowAllStocksService();
        $stocks = $service->execute($stockSymbols);
        return new View("topStocks", ["stocks" => $stocks->getStocks()]);
    }
}

