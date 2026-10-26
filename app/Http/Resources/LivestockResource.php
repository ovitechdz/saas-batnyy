<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LivestockResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'farm_id' => $this->farm_id,
            'rfid_tag' => $this->rfid_tag,
            'qr_code' => $this->qr_code,
            'name' => $this->name,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date ? $this->birth_date->format('Y-m-d') : null,
            'status' => $this->status,
            'mother_id' => $this->mother_id,
            'father_id' => $this->father_id,
            'created_at' => $this->created_at ? $this->created_at->toDateTimeString() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toDateTimeString() : null,
        ];
    }
}
