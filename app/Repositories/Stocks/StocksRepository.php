<?php

namespace App\Repositories\Stocks;

use App\Models\Collections\StocksCollection;

interface StocksRepository
{
    public function getAll(array $stockSymbols): StocksCollection;
}