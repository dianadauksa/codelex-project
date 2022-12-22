<?php

namespace App\Services\Stock;

use App\Models\{Stock, Collections\StocksCollection};
use App\Repositories\Stocks\StocksRepository;

class ShowAllStocksService
{
    private StocksRepository $stocksRepository;
    public function __construct(StocksRepository $repository)
    {
        $this->stocksRepository = $repository;
    }

    public function execute(array $stockSymbols): StocksCollection
    {
        return $this->stocksRepository->getAll($stockSymbols);
    }

    public function executeSingle(string $stockSymbol): Stock
    {
        return $this->stocksRepository->getSingle($stockSymbol);
    }
}