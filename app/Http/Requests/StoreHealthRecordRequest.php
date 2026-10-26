<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHealthRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sheep_id' => 'required|uuid|exists:sheep,id',
            'date_recorded' => 'required|date',
            'diagnosis' => 'required|string',
            'treatment' => 'required|string',
            'vet_id' => 'nullable|uuid|exists:users,id',
        ];
    }
}
