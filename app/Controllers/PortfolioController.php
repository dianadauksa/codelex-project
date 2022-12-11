<?php

namespace App\Controllers;

use App\View;

class PortfolioController
{
    public function index(): View
    {
        return new View("myStocks");
    }
}