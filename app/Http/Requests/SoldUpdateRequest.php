<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SoldUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'cow_id' => ['required', 'exists:cows,id'],
            'pounds' => ['nullable', 'numeric'],
            'kilograms' => ['nullable', 'numeric'],
            'price' => ['nullable', 'numeric'],
            'number_sold' => ['nullable', 'max:255', 'string'],
        ];
    }
}
