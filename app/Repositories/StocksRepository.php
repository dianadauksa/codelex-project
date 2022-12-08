<?php

namespace App\Repositories;

use App\Models\Collections\StocksCollection;

interface StocksRepository
{
    public function getStocks(array $stockSymbols): StocksCollection;
}