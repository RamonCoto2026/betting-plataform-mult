<?php

namespace App\Services;

use App\Models\Cryptocurrency;
use App\Models\CryptoDeposit;
use App\Models\CryptoWithdrawal;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CryptoWalletService
{
    protected $binanceService;

    public function __construct(BinanceService $binanceService)
    {
        $this->binanceService = $binanceService;
    }

    /**
     * Create a crypto deposit record
     */
    public function createDeposit(User $user, Cryptocurrency $crypto, $amount, $walletAddress, $txHash)
    {
        try {
            $deposit = CryptoDeposit::create([
                'user_id' => $user->id,
                'crypto_id' => $crypto->id,
                'amount' => $amount,
                'wallet_address' => $walletAddress,
                'transaction_hash' => $txHash,
                'status' => CryptoDeposit::PENDING,
                'confirmations' => 0,
                'required_confirmations' => $this->getRequiredConfirmations($crypto->symbol),
            ]);

            return $deposit;
        } catch (\Exception $e) {
            Log::error('Crypto Deposit Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Confirm a crypto deposit
     */
    public function confirmDeposit(CryptoDeposit $deposit, $currentConfirmations = null)
    {
        try {
            // Update confirmations
            if ($currentConfirmations !== null) {
                $deposit->confirmations = $currentConfirmations;
            }

            // Check if enough confirmations
            if ($deposit->confirmations >= $deposit->required_confirmations) {
                $deposit->status = CryptoDeposit::CONFIRMED;
                $deposit->save();

                // Add balance to user
                $user = $deposit->user;
                $user->balance += $deposit->amount;
                $user->save();

                return true;
            }

            $deposit->save();
            return false;
        } catch (\Exception $e) {
            Log::error('Deposit Confirmation Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Create a crypto withdrawal request
     */
    public function createWithdrawal(User $user, Cryptocurrency $crypto, $amount, $walletAddress)
    {
        try {
            // Validate amount
            if ($amount > $user->balance) {
                return [
                    'success' => false,
                    'message' => 'Insufficient balance'
                ];
            }

            if ($amount < $crypto->min_bet) {
                return [
                    'success' => false,
                    'message' => 'Amount is below minimum withdrawal'
                ];
            }

            // Calculate network fee (example: 0.1% or fixed amount)
            $networkFee = $this->calculateNetworkFee($crypto->symbol, $amount);

            // Create withdrawal record
            $withdrawal = CryptoWithdrawal::create([
                'user_id' => $user->id,
                'crypto_id' => $crypto->id,
                'amount' => $amount,
                'wallet_address' => $walletAddress,
                'network_fee' => $networkFee,
                'status' => CryptoWithdrawal::PENDING,
            ]);

            // Deduct from user balance (hold the amount)
            $user->balance -= ($amount + $networkFee);
            $user->save();

            return [
                'success' => true,
                'withdrawal' => $withdrawal,
                'message' => 'Withdrawal request created'
            ];
        } catch (\Exception $e) {
            Log::error('Withdrawal Creation Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Process a crypto withdrawal (send to blockchain)
     */
    public function processWithdrawal(CryptoWithdrawal $withdrawal, $txHash)
    {
        try {
            $withdrawal->transaction_hash = $txHash;
            $withdrawal->status = CryptoWithdrawal::PROCESSING;
            $withdrawal->processed_at = now();
            $withdrawal->save();

            return true;
        } catch (\Exception $e) {
            Log::error('Withdrawal Processing Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Mark withdrawal as completed
     */
    public function completeWithdrawal(CryptoWithdrawal $withdrawal)
    {
        try {
            $withdrawal->status = CryptoWithdrawal::COMPLETED;
            $withdrawal->save();

            return true;
        } catch (\Exception $e) {
            Log::error('Withdrawal Completion Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Reject a withdrawal (refund to user)
     */
    public function rejectWithdrawal(CryptoWithdrawal $withdrawal)
    {
        try {
            $withdrawal->status = CryptoWithdrawal::FAILED;
            $withdrawal->save();

            // Refund to user balance
            $user = $withdrawal->user;
            $user->balance += ($withdrawal->amount + $withdrawal->network_fee);
            $user->save();

            return true;
        } catch (\Exception $e) {
            Log::error('Withdrawal Rejection Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get user crypto balance by currency
     */
    public function getUserCryptoBalance(User $user, Cryptocurrency $crypto)
    {
        // For now, we use a unified balance
        // You can implement per-crypto balance in the future
        return $user->balance;
    }

    /**
     * Get required confirmations by blockchain
     */
    private function getRequiredConfirmations($symbol)
    {
        $confirmations = [
            'BTC' => 3,
            'ETH' => 12,
            'USDT' => 12,
            'BNB' => 1,
            'XRP' => 30,
        ];

        return $confirmations[$symbol] ?? 6;
    }

    /**
     * Calculate network fee
     */
    private function calculateNetworkFee($symbol, $amount)
    {
        // Example: 0.5% fee with minimum
        $percentage = 0.005;
        $minimumFee = 0.00001;
        $fee = $amount * $percentage;

        return max($fee, $minimumFee);
    }
}
