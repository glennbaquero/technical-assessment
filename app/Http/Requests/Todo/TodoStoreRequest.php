<?php

namespace App\Http\Requests\Todo;

use App\Concerns\TodoValidationRules;
use Illuminate\Foundation\Http\FormRequest;

class TodoStoreRequest extends FormRequest
{
    use TodoValidationRules;

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'title' => $this->titleRules(),
            'description' => $this->descriptionRules(),
        ];
    }
}
