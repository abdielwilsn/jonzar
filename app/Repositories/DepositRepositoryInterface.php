<?php

namespace App\Repositories;

interface DepositRepositoryInterface
{
    public function find($id);
    public function delete($id);
    public function updateStatus($id, string $status);
    public function hasProcessedDeposits($userId, $excludeDepositId);
}
