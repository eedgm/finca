<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MedicineStoreRequest extends FormRequest
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
            'name' => ['required', 'max:255', 'string'],
            'manufacturer_id' => ['required', 'exists:manufacturers,id'],
            'expiration_date' => ['nullable', 'date'],
            'code' => ['nullable', 'max:255', 'string'],
            'cc' => ['nullable', 'numeric'],
            'cost' => ['nullable', 'numeric'],
            'market_id' => ['required', 'exists:markets,id'],
            'picture' => ['image', 'max:5000', 'nullable'],
        ];
    }
}
