<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBreedingCycleRequest extends FormRequest
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
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'sheep_id' => 'required|exists:sheep,id',
            'mating_date' => 'required|date',
            'expected_birth_date' => 'nullable|date|after:mating_date',
            'actual_birth_date' => 'nullable|date|after:mating_date',
            'offspring_count' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
        ];
    }
}
