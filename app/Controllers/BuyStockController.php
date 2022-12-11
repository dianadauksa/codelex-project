<?php

namespace App\Controllers;

use App\Services\Stock\ShowAllStocksService;
use App\View;

class BuyStockController
{
    public function index(): View
    {
        $stockSymbol = [strtoupper($_GET['symbol'])];
        $service = new ShowAllStocksService();
        $stocks = $service->execute($stockSymbol);
        return new View("singleStock", ["stocks" => $stocks->getStocks()]);
    }
}
