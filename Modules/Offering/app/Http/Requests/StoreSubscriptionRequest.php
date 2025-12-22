<?php

namespace Modules\Offering\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubscriptionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'frequency' => ['required', 'string', 'in:instant,daily'],
            'filters' => ['required', 'array'],
            'filters.companies' => ['array'],
            'filters.companies.*' => ['integer'],
            'filters.processes' => ['array'],
            'filters.processes.*' => ['integer'],
            'filters.flavor_notes' => ['array'],
            'filters.flavor_notes.*' => ['integer'],
            'filters.varieties' => ['array'],
            'filters.varieties.*' => ['integer'],
            'filters.countries' => ['array'],
            'filters.countries.*' => ['integer'],
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
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'frequency.in' => 'Please select a valid notification frequency.',
            'filters.required' => 'At least one filter must be selected.',
        ];
    }
}

