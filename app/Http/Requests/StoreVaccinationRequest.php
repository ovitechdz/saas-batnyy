<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreVaccinationRequest extends FormRequest
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
            'vaccine_name' => 'required|string|max:255',
            'dose' => 'nullable|string|max:50',
            'date_administered' => 'required|date',
            'next_due_date' => 'nullable|date|after:date_administered',
            'administered_by' => 'nullable|exists:users,id',
        ];
    }
}
