<?php

namespace App\Repositories\Stocks;

use App\Models\{Stock,Collections\StocksCollection};

interface StocksRepository
{
    public function getAll(array $stockSymbols): StocksCollection;
    public function getSingle(string $stockSymbol): Stock;
}