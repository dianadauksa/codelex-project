<?php

namespace App\Models;

class UserStock
{
    private string $symbol;
    private float $currentPrice;
    private int $amountOwned;
    private float $change;
    private float $averagePrice;

    public function __construct(string $symbol, float $currentPrice, int $amountOwned, float $change, float $averagePrice)
    {
        $this->symbol = $symbol;
        $this->currentPrice = $currentPrice;
        $this->amountOwned = $amountOwned;
        $this->change = $change;
        $this->averagePrice = $averagePrice;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getCurrentPrice(): string
    {
        return $this->currentPrice;
    }

    public function getAmountOwned(): int
    {
        return $this->amountOwned;
    }

    public function getChange(): string
    {
        return $this->change;
    }

    public function getAveragePrice(): string
    {
        return $this->averagePrice;
    }
}