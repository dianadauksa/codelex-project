<?php

namespace App\Controllers;

use App\Repositories\APIStocksRepository;
use App\Services\ShowAllStocksService;
use App\View;

class StockController
{
    private ShowAllStocksService $service;

    public function __construct()
    {
        $this->service = new ShowAllStocksService(new APIStocksRepository());
    }

    public function index(): View
    {
        $stocks = $this->service->execute();
        return new View("stocks", ["stocks" => $stocks->getStocks()]);
    }
}

