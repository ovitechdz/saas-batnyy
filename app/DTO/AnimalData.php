<?php

namespace App\DTO;

use Illuminate\Http\Request;

class AnimalData
{
    public function __construct(
        public ?string $farm_id,
        public ?string $internal_id,
        public ?string $visual_tag,
        public ?string $electronic_tag,
        public ?string $name,
        public ?string $species_id,
        public ?string $breed_id,
        public ?string $category_id,
        public ?string $status_id,
        public string $gender,
        public ?string $color,
        public ?string $horn_status,
        public ?string $wool_type,
        public ?string $birth_date,
        public ?float $birth_weight,
        public ?float $body_condition_score,
        public ?float $current_market_value,
        public ?string $origin,
        public ?string $insurance_policy,
        public ?string $mother_id,
        public ?string $father_id,
        public ?string $pen_id,
        public ?string $barn_id,
        public ?string $feed_group_id,
        public ?\Illuminate\Http\UploadedFile $photo = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            farm_id: $request->user()->farm_id ?? \App\Models\Farm::first()->id ?? null,
            internal_id: $request->input('internal_id'),
            visual_tag: $request->input('visual_tag'),
            electronic_tag: $request->input('electronic_tag'),
            name: $request->input('name'),
            species_id: $request->input('species_id'),
            breed_id: $request->input('breed_id'),
            category_id: $request->input('category_id'),
            status_id: $request->input('status_id'),
            gender: $request->input('gender', 'Unknown'),
            color: $request->input('color'),
            horn_status: $request->input('horn_status'),
            wool_type: $request->input('wool_type'),
            birth_date: $request->input('birth_date'),
            birth_weight: $request->input('birth_weight') ? (float) $request->input('birth_weight') : null,
            body_condition_score: $request->input('body_condition_score') ? (float) $request->input('body_condition_score') : null,
            current_market_value: $request->input('current_market_value') ? (float) $request->input('current_market_value') : null,
            origin: $request->input('origin'),
            insurance_policy: $request->input('insurance_policy'),
            mother_id: $request->input('mother_id'),
            father_id: $request->input('father_id'),
            pen_id: $request->input('pen_id'),
            barn_id: $request->input('barn_id'),
            feed_group_id: $request->input('feed_group_id'),
            photo: $request->file('photo'),
        );
    }

    public function toArray(): array
    {
        return array_filter((array) $this, fn($value) => !is_null($value));
    }
}
