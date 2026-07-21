<?php

namespace App\Services;

use App\Models\Settings;
use App\Models\Wdmethod;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Transaction;

class CheckDepositsService
{
    protected $apiKey;
    protected $walletAddress;

    public function __construct()
    {
        $this->apiKey = config('services.etherscan.api_key');

        $settings = Wdmethod::where('id', '1')->first();

        $this->walletAddress = $settings->wallet_address;

    }

    public function checkDeposits()
    {
        $tokens = [
            'USDT' => '0x55d398326f99059fF775485246999027B3197955',
            'USDC' => '0x8ac76a51cc950d9822d68b83fe1ad97b32cd580d',
        ];

        $chainId = 56;
        $baseUrl = 'https://api.etherscan.io/v2/api';

        foreach ($tokens as $symbol => $contract) {
            Log::info("🔍 Checking blockchain for {$symbol} deposits using Etherscan V2 (BSC ChainID: {$chainId})...");

            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
            ])->get($baseUrl, [
                'chainid' => $chainId,
                'module' => 'account',
                'action' => 'tokentx',
                'contractaddress' => $contract,
                'address' => $this->walletAddress,
                'sort' => 'desc',
                'page' => 1,
                'offset' => 50,
            ]);

            if ($response->failed()) {
                Log::error("❌ Etherscan V2 API request failed for {$symbol}: " . $response->body());
                continue;
            }

            $data = $response->json();

            if (!isset($data['result']) || !is_array($data['result'])) {
                Log::error("⚠️ Invalid Etherscan V2 API response for {$symbol}: " . json_encode($data));
                continue;
            }

            $transactions = $data['result'];
            Log::info("✅ Found " . count($transactions) . " {$symbol} transactions on-chain for wallet {$this->walletAddress}");

            $this->matchTransactions($transactions, $symbol);
        }
    }

    protected function matchTransactions(array $onChainTxs, string $symbol)
    {
        $pending = Transaction::where('status', 'pending')
            ->where('token_symbol', $symbol)
            ->get();

        Log::info("📋 Found {$pending->count()} pending {$symbol} deposits to match...");

        foreach ($pending as $pendingTx) {
            Log::info("🧾 Checking pending transaction ID {$pendingTx->id}, amount: {$pendingTx->amount}, user_id: {$pendingTx->user_id}");

            // 1️⃣ If user provided proof text (hash)
            if ($pendingTx->proof_text) {
                $match = collect($onChainTxs)->firstWhere('hash', $pendingTx->proof_text);
                if ($match) {
                    if (strtolower($match['to']) === $this->walletAddress) {
                        Log::info("✅ Found exact hash match for transaction ID {$pendingTx->id}: {$match['hash']}");
                        $this->confirmTransaction($pendingTx, $match['hash']);
                        continue;
                    } else {
                        Log::warning("⚠️ Hash {$pendingTx->proof_text} found on chain but sent to a different wallet: {$match['to']}");
                    }
                } else {
                    Log::warning("❌ No blockchain transaction found for provided hash: {$pendingTx->proof_text}");
                }
            }

            // 2️⃣ No proof hash or not found — match by amount
            foreach ($onChainTxs as $tx) {
                if (strtolower($tx['to']) !== $this->walletAddress) continue;

                $amount = $tx['value'] / pow(10, $tx['tokenDecimal']);

                if (abs($amount - $pendingTx->amount) < 0.000001) {
                    Log::info("💰 Matching on-chain {$symbol} tx found by amount ({$amount}) for transaction ID {$pendingTx->id}");

                    $duplicates = Transaction::where('amount', $pendingTx->amount)
                        ->where('token_symbol', $symbol)
                        ->whereBetween('created_at', [
                            now()->subMinutes(30),
                            now()->addMinutes(30),
                        ])
                        ->count();

                    if ($duplicates > 1) {
                        Log::warning("⚠️ Duplicate deposit detected for {$symbol} amount {$pendingTx->amount}. Escalating for review.");
                        $pendingTx->update([
                            'flagged' => true,
                            'status' => 'needs_review',
                        ]);
                        continue;
                    }

                    $this->confirmTransaction($pendingTx, $tx['hash']);
                    break;
                }
            }

            Log::info("⏳ Finished checking pending transaction ID {$pendingTx->id}");
        }
    }

    protected function confirmTransaction(Transaction $pendingTx, string $hash)
    {
        Log::info("🎯 Confirming transaction ID {$pendingTx->id} with hash {$hash}");

        $pendingTx->update([
            'tx_hash' => $hash,
            'status' => 'confirmed',
            'flagged' => false,
        ]);

        $user = $pendingTx->user;
        $user->increment('balance', $pendingTx->amount);

        Log::info("✅ User ID {$user->id} credited with {$pendingTx->amount}. Transaction confirmed.");
    }
}
