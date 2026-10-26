<?php

namespace App\Repositories\Contracts;

interface SheepRepositoryInterface extends BaseRepositoryInterface
{
    public function findByRfid(string $rfidTag);
    public function findByQrCode(string $qrCode);
    public function getByFarm(string $farmId);
}
