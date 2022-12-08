<?php

namespace App\Services;

use App\Models\Collections\StocksCollection;
use App\Repositories\FinnhubAPIStocksRepository;
use App\Repositories\StocksRepository;

class ShowAllStocksService
{
    private StocksRepository $stocksRepository;
    public function __construct()
    {
        $this->stocksRepository = new FinnhubAPIStocksRepository();
    }

    public function execute(array $stockSymbols): StocksCollection
    {
        return $this->stocksRepository->getStocks($stockSymbols);
    }
}