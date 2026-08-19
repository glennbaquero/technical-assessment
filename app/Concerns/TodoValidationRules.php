<?php

namespace App\Concerns;

trait TodoValidationRules
{
    /**
     * @return array<int, string>
     */
    protected function titleRules(): array
    {
        return ['required', 'string', 'max:255'];
    }

    /**
     * @return array<int, string>
     */
    protected function descriptionRules(): array
    {
        return ['nullable', 'string', 'max:2000'];
    }
}
