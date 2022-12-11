<?php

namespace App\Models\Collections;

use App\Models\Stock;

class StocksCollection
{
    private array $stocks = [];

    public function __construct(array $stocks = [])
    {
        foreach ($stocks as $stock) {
            $this->add($stock);
        }
    }

    public function add(Stock $stock): void
    {
        $this->stocks[] = $stock;
    }

    public function getStocks(): array
    {
        return $this->stocks;
    }
}
