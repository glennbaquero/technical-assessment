<?php

namespace App\Concerns;

trait TodoValidationRules
{
    protected function titleRules(): array
    {
        return ['required', 'string', 'max:255'];
    }

    protected function descriptionRules(): array
    {
        return ['nullable', 'string', 'max:2000'];
    }
}
