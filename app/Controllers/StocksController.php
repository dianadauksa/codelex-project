<?php

namespace App\Controllers;

use App\Services\Stock\ShowAllStocksService;
use App\View;

class StocksController
{
    private ShowAllStocksService $service;

    public function __construct(ShowAllStocksService $service)
    {
        $this->service = $service;
    }

    public function index(): View
    {
        $stockSymbols = ['AAPL', 'GOOG', 'MSFT', 'AMZN', 'META', 'INTC', 'TSLA', 'ORCL', 'IBM', 'HPQ','SONY', 'NVDA'];
        //$service = new ShowAllStocksService(); uses DI
        $stocks = $this->service->execute($stockSymbols);
        return new View("topStocks", ["stocks" => $stocks->getAllStocks()]);
    }
}

