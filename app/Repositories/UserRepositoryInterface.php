<?php

namespace App\Repositories;

interface UserRepositoryInterface
{
    public function find(int $id);
    public function updateBalance(int $id, float $amount);
    public function updateRefBonus(int $id, float $amount);
    public function markRefByPaid(int $id);
}
