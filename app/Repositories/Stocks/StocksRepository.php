<?php

namespace App\Repositories\Stocks;

use App\Models\Collections\StocksCollection;

interface StocksRepository
{
    public function getStocks(array $stockSymbols): StocksCollection;
}