<?php

namespace App\Actions\Deposit;

use App\Repositories\DepositRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Services\ReferralService;
use Illuminate\Support\Facades\DB;

class ProcessDepositAction
{
    private $depositRepository;
    private $userRepository;
    private $referralService;

    public function __construct(
        DepositRepositoryInterface $depositRepository,
        UserRepositoryInterface $userRepository,
        ReferralService $referralService
    ) {
        $this->depositRepository = $depositRepository;
        $this->userRepository = $userRepository;
        $this->referralService = $referralService;
    }

    public function execute($id)
    {
        return DB::transaction(function () use ($id) {
            $deposit = $this->depositRepository->find($id);
            if (!$deposit) {
                throw new \Exception('Deposit not found!');
            }


            if ($deposit->status === 'Processed') {
                throw new \Exception('Deposit already processed!');
            }

            $user = $this->userRepository->find($deposit->user);
            if (!$user || $deposit->user != $user->id) {
                throw new \Exception('Invalid user for this deposit!');
            }


            $isFirstDeposit = !$this->depositRepository->hasProcessedDeposits($user->id, $id);



            $this->userRepository->updateBalance($user->id, $deposit->amount);



            if ($isFirstDeposit) {

                $this->referralService->handleDirectReferralBonus($user, $deposit->amount);
                $this->referralService->handleAncestorBonuses($user->id, $deposit->amount);
            }

            $this->depositRepository->updateStatus($id, 'Processed');

        });
    }
}
