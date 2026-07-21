<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Actions\Deposit\DeleteDepositAction;
use App\Actions\Deposit\ProcessDepositAction;
use App\Repositories\DepositRepositoryInterface;
use Illuminate\Http\Request;

class ManageDepositController extends Controller
{
    private $depositRepository;
    private $processDepositAction;
    private $deleteDepositAction;

    public function __construct(
        DepositRepositoryInterface $depositRepository,
        ProcessDepositAction $processDepositAction,
        DeleteDepositAction $deleteDepositAction
    ) {
        $this->depositRepository = $depositRepository;
        $this->processDepositAction = $processDepositAction;
        $this->deleteDepositAction = $deleteDepositAction;
    }

    // Delete deposit
    public function deldeposit($id)
    {
        $this->deleteDepositAction->execute($id);
        return redirect()->back()->with('success', 'Deposit history has been deleted!');
    }

    // Process deposit
    public function pdeposit($id)
    {
        $this->processDepositAction->execute($id);
        return redirect()->back()->with('success', 'Action Successful!');
    }

    // View deposit image
    public function viewdepositimage($id)
    {
        $deposit = $this->depositRepository->find($id);
        return view('admin.Deposits.depositimg', [
            'deposit' => $deposit,
            'title' => 'View Deposit Screenshot',
            'settings' => \App\Models\Settings::where('id', 1)->first(),
        ]);
    }
}
