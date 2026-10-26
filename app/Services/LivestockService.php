<?php

namespace App\Services;

use App\DTO\AnimalData;
use App\Models\Animal;
use App\Repositories\Contracts\AnimalRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class LivestockService
{
    public function __construct(
        protected AnimalRepositoryInterface $animalRepository
    ) {}

    public function getFarmLivestock(string $farmId, array $filters, int $perPage = 50): LengthAwarePaginator
    {
        return $this->animalRepository->paginateFarmAnimals($farmId, $filters, $perPage);
    }

    public function getAnimalDetails(string $id): ?Animal
    {
        return $this->animalRepository->findWithDetails($id);
    }

    public function registerAnimal(AnimalData $data, string $userId): Animal
    {
        return DB::transaction(function () use ($data, $userId) {
            $animal = $this->animalRepository->create($data, $userId);
            
            // If birth_weight is provided, create the initial weight record
            if ($data->birth_weight) {
                $animal->weights()->create([
                    'weight_kg' => $data->birth_weight,
                    'measurement_date' => $data->birth_date ?? now(),
                ]);
            }
            // If photo is provided, save it as primary photo
            if ($data->photo) {
                $path = $data->photo->store('animal_photos', 'public');
                $animal->photos()->create([
                    'photo_path' => $path,
                    'is_primary' => true,
                    'uploaded_by' => $userId,
                ]);
            }

            return $animal;
        });
    }

    public function updateAnimal(string $id, AnimalData $data, string $userId): bool
    {
        return $this->animalRepository->update($id, $data, $userId);
    }

    public function removeAnimal(string $id): bool
    {
        return $this->animalRepository->delete($id);
    }

    public function processBulkAction(string $action, array $animalIds, array $payload, string $userId): array
    {
        return DB::transaction(function () use ($action, $animalIds, $payload, $userId) {
            $count = 0;
            switch ($action) {
                case 'delete':
                    $count = $this->animalRepository->bulkDelete($animalIds);
                    break;
                case 'change_status':
                    if (isset($payload['status_id'])) {
                        $count = $this->animalRepository->bulkUpdateStatus($animalIds, $payload['status_id'], $userId);
                    }
                    break;
                // other actions like 'vaccinate', 'transfer' can be handled here
            }
            return ['success' => true, 'count' => $count];
        });
    }
}
