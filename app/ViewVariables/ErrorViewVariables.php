<?php

namespace App\ViewVariables;

class ErrorViewVariables implements ViewVariables
{
    public function getName(): string
    {
        return 'errors';
    }

    public function getValue(): array
    {
        return $_SESSION['errors'] ?? [];
    }
}