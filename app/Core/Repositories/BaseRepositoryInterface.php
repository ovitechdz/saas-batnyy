<?php

namespace App\Core\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

interface BaseRepositoryInterface
{
    public function all(array $columns = ['*']): Collection;
    
    public function find(string $id, array $columns = ['*']): ?Model;
    
    public function create(array $data): Model;
    
    public function update(string $id, array $data): bool;
    
    public function delete(string $id): bool;
}
