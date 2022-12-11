<?php

namespace App\Models;

class Stock
{
    private string $symbol;
    private float $currentPrice;
    private float $change;

    public function __construct(string $symbol, float $currentPrice, float $change)
    {
        $this->symbol = $symbol;
        $this->currentPrice = $currentPrice;
        $this->change = $change;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getCurrentPrice(): string
    {
        return $this->currentPrice;
    }

    public function getChange(): string
    {
        return $this->change;
    }
}