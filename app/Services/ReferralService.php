<?php

namespace App\Services;

use App\Models\Agent;
use App\Models\Settings;
use App\Models\Tp_Transaction;
use App\Models\UserReferralSetting;
use App\Repositories\UserRepositoryInterface;

class ReferralService
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handleDirectReferralBonus($user, $depositAmount)
    {
        if (empty($user->ref_by) || $user->ref_by_paid) {
            return;
        }


        $referrerSettings = UserReferralSetting::where('user_id', $user->ref_by)->first();
        $globalSettings = Settings::where('id', 1)->first();

        $commissionRate = $referrerSettings ? $referrerSettings->referral_commission : $globalSettings->referral_commission;
        $earnings = $commissionRate * $depositAmount / 100;

        Agent::where('agent', $user->ref_by)->increment('total_activated', 1);
        Agent::where('agent', $user->ref_by)->increment('earnings', $earnings);


        $this->userRepository->updateRefBonus($user->ref_by, $earnings);


        Tp_Transaction::create([
            'user' => $user->ref_by,
            'from_user' => $user->id,
            'plan' => 'Credit',
            'amount' => $earnings,
            'type' => 'Ref_bonus',
        ]);


        $this->userRepository->markRefByPaid($user->id);
    }

    /**
     * Pay the trader's direct upline a commission on a profitable trade.
     *
     * Uses the admin-set global `trade_profit_commission` percentage. The
     * commission is credited to the referrer and returned to the caller so it
     * can be deducted from the trader's net proceeds / realized P/L.
     *
     * @return float  The commission amount taken from the trader's profit (0 if none).
     */
    public function handleTradeProfitCommission($trader, float $profit): float
    {
        // Only profitable trades with a referrer earn a commission.
        if ($profit <= 0 || empty($trader->ref_by)) {
            return 0.0;
        }

        $globalSettings = Settings::where('id', 1)->first();
        $rate = (float) ($globalSettings->trade_profit_commission ?? 0);

        if ($rate <= 0) {
            return 0.0;
        }

        $commission = $profit * $rate / 100;

        // Credit the direct upline's balance.
        $this->userRepository->updateRefBonus($trader->ref_by, $commission);

        Tp_Transaction::create([
            'user' => $trader->ref_by,
            'from_user' => $trader->id,
            'plan' => 'Credit',
            'amount' => $commission,
            'type' => 'Trade_commission',
        ]);

        return $commission;
    }

    public function handleAncestorBonuses($userId, $depositAmount)
    {
        $users = \App\Models\User::all();
        // The depositor whose action generated every bonus down this chain.
        $this->calculateAncestorBonuses($users, $depositAmount, $userId, 0, $userId);
    }

    private function calculateAncestorBonuses($users, $depositAmount, $parentId, $level = 0, $originUserId = null)
    {
        if ($level >= 5) {
            return;
        }

        $parent = $this->userRepository->find($parentId);
        if (!$parent || empty($parent->ref_by)) {
            return;
        }

        foreach ($users as $user) {
            if ($user->id == $parent->ref_by) {
                $ancestorSettings = UserReferralSetting::where('user_id', $user->id)->first();
                $globalSettings = Settings::where('id', 1)->first();

                $commissionRate = $this->getCommissionRate($ancestorSettings, $globalSettings, $level + 1);
                $earnings = $commissionRate * $depositAmount / 100;

                if ($earnings > 0) {
                    $this->userRepository->updateRefBonus($user->id, $earnings);
                    Tp_Transaction::create([
                        'user' => $user->id,
                        'from_user' => $originUserId,
                        'plan' => 'Credit',
                        'amount' => $earnings,
                        'type' => 'Ref_bonus',
                    ]);
                }

                $this->calculateAncestorBonuses($users, $depositAmount, $user->id, $level + 1, $originUserId);
            }
        }
    }

    private function getCommissionRate($ancestorSettings, $globalSettings, $level)
    {
        $field = "referral_commission$level";
        return $ancestorSettings ? $ancestorSettings->$field : $globalSettings->$field;
    }
}
