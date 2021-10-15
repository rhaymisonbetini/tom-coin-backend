<?php

namespace App\Repositories;

use App\Models\Wallet;

class WalletRepository
{
    private $tomCoinHistoryRepository;

    public function __construct(TomCoinHistoryRepository $tomCoinHistoryRepository)
    {
        $this->tomCoinHistoryRepository = $tomCoinHistoryRepository;
    }

    public function getUserWallet($userId): mixed
    {
        return Wallet::where('id_user', $userId)->firstOrFail();
    }

    public function updateUserMinerate($wallet): mixed
    {
        $actualValue =  Wallet::where('public_key', $wallet)->first()->cash;
        return Wallet::where('public_key', $wallet)->update(['cash' => 1 + $actualValue]);
    }

    public function getUserCashByPublicKey($wallet): float
    {
        return Wallet::where('public_key', $wallet)->first()->cash;
    }
}
