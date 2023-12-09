<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CowStoreRequest extends FormRequest
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
            'number' => ['nullable', 'numeric'],
            'name' => ['nullable', 'max:255', 'string'],
            'gender' => ['required', 'in:male,female'],
            'parent_id' => ['nullable', 'max:255'],
            'mother_id' => ['nullable', 'max:255'],
            'farm_id' => ['required', 'exists:farms,id'],
            'owner' => ['nullable', 'max:255', 'string'],
            'sold' => ['required', 'boolean'],
            'picture' => ['image', 'max:1024', 'nullable'],
            'born' => ['nullable', 'date'],
        ];
    }
}
