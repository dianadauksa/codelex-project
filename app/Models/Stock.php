<?php

namespace App\Models;

class Stock
{
    private string $symbol;
    private string $name;
    private string $currency;
    private float $currentPrice;
    private float $change;

    public function __construct(string $symbol, string $name, string $currency, float $currentPrice, float $change)
    {
        $this->symbol = $symbol;
        $this->name = $name;
        $this->currency = $currency;
        $this->currentPrice = number_format($currentPrice,2);
        $this->change = number_format($change,2);

    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCurrency(): string
    {
        return $this->currency;
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