<?php

namespace App\Http\Requests\Todo;

use App\Concerns\TodoValidationRules;
use Illuminate\Foundation\Http\FormRequest;

class TodoUpdateRequest extends FormRequest
{
    use TodoValidationRules;

    public function rules(): array
    {
        return [
            'title' => $this->titleRules(),
            'description' => $this->descriptionRules(),
        ];
    }
}
