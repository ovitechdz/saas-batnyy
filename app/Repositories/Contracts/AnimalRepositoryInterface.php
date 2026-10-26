<?php

namespace App\Repositories\Contracts;

use App\DTO\AnimalData;
use App\Models\Animal;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface AnimalRepositoryInterface
{
    public function paginateFarmAnimals(string $farmId, array $filters = [], int $perPage = 50): LengthAwarePaginator;
    public function findById(string $id): ?Animal;
    public function findWithDetails(string $id): ?Animal;
    public function create(AnimalData $data, string $userId): Animal;
    public function update(string $id, AnimalData $data, string $userId): bool;
    public function delete(string $id): bool;
    public function bulkDelete(array $ids): int;
    public function bulkUpdateStatus(array $ids, string $statusId, string $userId): int;
}
