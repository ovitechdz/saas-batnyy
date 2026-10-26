<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLivestockRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Handle RBAC authorization via policies/middleware later
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'farm_id' => ['required', 'uuid', 'exists:farms,id'],
            'rfid_tag' => ['nullable', 'string', 'unique:sheep,rfid_tag'],
            'qr_code' => ['nullable', 'string', 'unique:sheep,qr_code'],
            'name' => ['nullable', 'string', 'max:255'],
            'gender' => ['required', 'in:ram,ewe,wether'],
            'birth_date' => ['required', 'date', 'before_or_equal:today'],
            'status' => ['required', 'in:healthy,sick,pregnant,sold,deceased'],
            'mother_id' => ['nullable', 'uuid', 'exists:sheep,id'],
            'father_id' => ['nullable', 'uuid', 'exists:sheep,id'],
        ];
    }
}
