<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HealthRecordResource extends JsonResource
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
            'sheep_id' => $this->sheep_id,
            'vet_id' => $this->vet_id,
            'diagnosis' => $this->diagnosis,
            'treatment' => $this->treatment,
            'date_recorded' => $this->date_recorded ? \Carbon\Carbon::parse($this->date_recorded)->format('Y-m-d H:i:s') : null,
            'created_at' => $this->created_at ? $this->created_at->toDateTimeString() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toDateTimeString() : null,
        ];
    }
}
