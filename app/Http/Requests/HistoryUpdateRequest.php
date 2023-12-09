<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HistoryUpdateRequest extends FormRequest
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
            'weight' => ['nullable', 'numeric'],
            'cow_type_id' => ['nullable', 'exists:cow_types,id'],
            'comments' => ['nullable', 'max:255', 'string'],
            'picture' => ['image', 'max:1024', 'nullable'],
        ];
    }
}
