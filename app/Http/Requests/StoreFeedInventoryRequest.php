<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreFeedInventoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'farm_id' => 'required|uuid|exists:farms,id',
            'feed_type' => 'required|string|max:255',
            'quantity_kg' => 'required|numeric|min:0',
            'reorder_level_kg' => 'required|numeric|min:0',
        ];
    }
}
