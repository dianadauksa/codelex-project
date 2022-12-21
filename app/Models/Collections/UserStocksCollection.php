<?php

namespace App\Models\Collections;

use App\Models\UserStock;

class UserStocksCollection
{
    private array $stocks = [];

    public function __construct(array $stocks = [])
    {
        foreach ($stocks as $stock) {
            $this->add($stock);
        }
    }

    public function add(UserStock $stock): void
    {
        $this->stocks[] = $stock;
    }

    public function getAll(): array
    {
        return $this->stocks;
    }
}