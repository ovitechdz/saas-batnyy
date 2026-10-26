<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BreedingCycleResource extends JsonResource
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
            'mating_date' => $this->mating_date,
            'expected_birth_date' => $this->expected_birth_date,
            'actual_birth_date' => $this->actual_birth_date,
            'offspring_count' => $this->offspring_count,
            'notes' => $this->notes,
            'created_at' => $this->created_at,
        ];
    }
}
