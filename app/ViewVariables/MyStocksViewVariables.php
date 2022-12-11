<?php

namespace App\ViewVariables;

class MyStocksViewVariables implements ViewVariables
{
    public function getName(): string
    {
        return 'portfolio';
    }

    public function getValue(): array
    {
        return $_SESSION['portfolio'] ?? [];
    }
}