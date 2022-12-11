<?php

namespace App\Services\Stock;

use App\Models\Collections\StocksCollection;
use App\Repositories\Stocks\FinnhubAPIStocksRepository;
use App\Repositories\Stocks\StocksRepository;

class ShowAllStocksService
{
    private StocksRepository $stocksRepository;
    public function __construct()
    {
        $this->stocksRepository = new FinnhubAPIStocksRepository();
    }

    public function execute(array $stockSymbols): StocksCollection
    {
        return $this->stocksRepository->getAll($stockSymbols);
    }
}