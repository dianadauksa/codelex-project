<?php

namespace App\Repositories\UserStocks;

use App\Models\Collections\UserStocksCollection;

interface UserStocksRepository
{
    public function getAll(array $stockSymbols): UserStocksCollection;
}