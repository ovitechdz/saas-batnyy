<?php

namespace App\Repositories\Eloquent;

use App\Models\Sheep;
use App\Repositories\Contracts\SheepRepositoryInterface;

class EloquentSheepRepository extends BaseRepository implements SheepRepositoryInterface
{
    public function __construct(Sheep $model)
    {
        parent::__construct($model);
    }

    public function findByRfid(string $rfidTag)
    {
        return $this->model->where('rfid_tag', $rfidTag)->first();
    }

    public function findByQrCode(string $qrCode)
    {
        return $this->model->where('qr_code', $qrCode)->first();
    }

    public function getByFarm(string $farmId)
    {
        return $this->model->where('farm_id', $farmId)->get();
    }
}
