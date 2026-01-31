<?php

namespace Modules\Recipe\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRecipeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'brew_method_id' => ['required', 'integer', 'exists:brew_methods,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'coffee_dose' => ['nullable', 'string', 'max:50'],
            'water_amount' => ['nullable', 'string', 'max:50'],
            'water_temperature' => ['nullable', 'string', 'max:50'],
            'grind_size' => ['nullable', 'string', 'max:100'],
            'total_brew_time' => ['nullable', 'string', 'max:50'],
            'yield' => ['nullable', 'string', 'max:50'],
            'is_public' => ['boolean'],
            'steps' => ['required', 'array', 'min:1'],
            'steps.*.title' => ['required', 'string', 'max:255'],
            'steps.*.description' => ['nullable', 'string', 'max:1000'],
            'steps.*.duration' => ['nullable', 'string', 'max:50'],
            'steps.*.water_amount' => ['nullable', 'string', 'max:50'],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'brew_method_id.required' => 'Please select a brew method.',
            'brew_method_id.exists' => 'The selected brew method is invalid.',
            'name.required' => 'Please enter a recipe name.',
            'steps.required' => 'Please add at least one step to your recipe.',
            'steps.min' => 'Please add at least one step to your recipe.',
            'steps.*.title.required' => 'Each step must have a title.',
        ];
    }
}



