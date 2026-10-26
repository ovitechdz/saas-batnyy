<?php

namespace App\Repositories;

use App\DTO\AnimalData;
use App\Models\Animal;
use App\Repositories\Contracts\AnimalRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class AnimalRepository implements AnimalRepositoryInterface
{
    public function paginateFarmAnimals(string $farmId, array $filters = [], int $perPage = 50): LengthAwarePaginator
    {
        $query = Animal::with(['breed', 'category', 'status', 'barn', 'photos' => function ($q) {
            $q->where('is_primary', true);
        }])->where('farm_id', $farmId);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('internal_id', 'ilike', "%{$search}%")
                  ->orWhere('electronic_tag', 'ilike', "%{$search}%")
                  ->orWhere('visual_tag', 'ilike', "%{$search}%");
            });
        }

        if (!empty($filters['status_id'])) {
            $query->where('status_id', $filters['status_id']);
        }

        if (!empty($filters['breed_id'])) {
            $query->where('breed_id', $filters['breed_id']);
        }
        
        if (!empty($filters['gender'])) {
            $query->where('gender', $filters['gender']);
        }

        return $query->latest()->paginate($perPage);
    }

    public function findById(string $id): ?Animal
    {
        return Animal::find($id);
    }

    public function findWithDetails(string $id): ?Animal
    {
        return Animal::with([
            'breed', 'category', 'status', 'barn', 'mother', 'father', 'offspring', 
            'photos', 'weights', 'healthRecords', 'vaccinations', 'movements', 'notes'
        ])->find($id);
    }

    public function create(AnimalData $data, string $userId): Animal
    {
        $attributes = $data->toArray();
        $attributes['created_by'] = $userId;
        $attributes['updated_by'] = $userId;
        
        return Animal::create($attributes);
    }

    public function update(string $id, AnimalData $data, string $userId): bool
    {
        $animal = Animal::findOrFail($id);
        
        $attributes = $data->toArray();
        $attributes['updated_by'] = $userId;
        
        return $animal->update($attributes);
    }

    public function delete(string $id): bool
    {
        $animal = Animal::findOrFail($id);
        return $animal->delete();
    }

    public function bulkDelete(array $ids): int
    {
        return Animal::whereIn('id', $ids)->delete();
    }

    public function bulkUpdateStatus(array $ids, string $statusId, string $userId): int
    {
        return Animal::whereIn('id', $ids)->update([
            'status_id' => $statusId,
            'updated_by' => $userId
        ]);
    }
}
