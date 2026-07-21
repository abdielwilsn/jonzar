<?php

namespace App\Repositories;

use App\Models\Deposit;
use Illuminate\Support\Facades\DB;

class DepositRepository implements DepositRepositoryInterface
{
    public function find($id)
    {
        return Deposit::where('id', $id)->lockForUpdate()->first();
    }

    public function delete($id)
    {
        Deposit::where('id', $id)->delete();
    }

    public function updateStatus($id, string $status)
    {
        Deposit::where('id', $id)->update(['status' => $status]);
    }

    public function hasProcessedDeposits($userId, $excludeDepositId)
    {

        return Deposit::where('user', $userId)
            ->where('status', 'Processed')
            ->where('id', '!=', $excludeDepositId)
            ->exists();
    }
}
