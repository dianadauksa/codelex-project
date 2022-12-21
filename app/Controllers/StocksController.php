<?php

namespace App\Controllers;

use App\Services\Stock\ShowAllStocksService;
use App\View;

class StocksController
{
    public function index(): View
    {
        $stockSymbols = ['AAPL', 'GOOG', 'MSFT', 'AMZN', 'META', 'INTC', 'TSLA', 'ORCL', 'IBM', 'HPQ','SONY', 'NVDA'];
        $service = new ShowAllStocksService();
        $stocks = $service->execute($stockSymbols);
        return new View("topStocks", ["stocks" => $stocks->getAllStocks()]);
    }
}

