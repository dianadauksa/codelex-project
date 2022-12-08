<?php

namespace App\Services;

use App\Models\Collections\StocksCollection;
use App\Repositories\StocksRepository;

class ShowAllStocksService
{
    private StocksRepository $stocksRepository;
    public function __construct(StocksRepository $stocksRepository)
    {
        $this->stocksRepository = $stocksRepository;
    }

    public function execute(): StocksCollection
    {
        return $this->stocksRepository->getStocks();
    }
}