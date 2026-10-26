<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VaccinationResource extends JsonResource
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
            'vaccine_name' => $this->vaccine_name,
            'dose' => $this->dose,
            'date_administered' => $this->date_administered,
            'next_due_date' => $this->next_due_date,
            'administered_by' => $this->administered_by,
            'created_at' => $this->created_at,
        ];
    }
}
