<?php

namespace App\Services\UserStock;

use App\Models\Collections\UserStocksCollection;
use App\Repositories\UserStocks\FinnhubAPIUserStocksRepository;
use App\Repositories\UserStocks\UserStocksRepository;

class ShowUserStocksService
{
    private UserStocksRepository $stocksRepository;
    public function __construct()
    {
        $this->stocksRepository = new FinnhubAPIUserStocksRepository();
    }

    public function execute(array $stockSymbols): UserStocksCollection
    {
        return $this->stocksRepository->getAll($stockSymbols);
    }
}