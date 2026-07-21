<?php

namespace App\Repositories;

use App\Models\Deposit;

class EloquentDepositRepository implements DepositRepositoryInterface
{
    public function all()
    {
        return Deposit::all();
    }

    public function find($id)
    {
        return Deposit::findOrFail($id);
    }

    public function create(array $data)
    {
        return Deposit::create($data);
    }

    public function delete($id)
    {
        return Deposit::destroy($id);
    }

    public function updateStatus($id, $status)
    {
        $deposit = Deposit::findOrFail($id);
        $deposit->status = $status;
        $deposit->save();

        return $deposit;
    }

    public function hasProcessedDeposits($userId, $excludeDepositId = null)
    {
        $query = Deposit::where('user', $userId)
                        ->where('status', 'processed');

        if ($excludeDepositId) {
            $query->where('id', '!=', $excludeDepositId);
        }

        return $query->exists();
    }

}
