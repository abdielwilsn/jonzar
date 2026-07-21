<?php

namespace App\Actions\Deposit;

use App\Repositories\DepositRepositoryInterface;

class DeleteDepositAction
{
    private $depositRepository;

    public function __construct(DepositRepositoryInterface $depositRepository)
    {
        $this->depositRepository = $depositRepository;
    }

    public function execute($id)
    {
        $this->depositRepository->delete($id);
    }
}
