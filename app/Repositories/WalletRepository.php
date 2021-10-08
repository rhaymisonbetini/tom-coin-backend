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

    public function updateUserMinerate($wallet): mixed
    {
        $actualValue =  Wallet::where('public_key', $wallet)->first()->cash;
        return Wallet::where('public_key', $wallet)->update(['cash' => $this->tomCoinHistoryRepository->lastValue() + $actualValue]);
    }

    public function getUserCashByPublicKey($wallet): float
    {
        return Wallet::where('public_key', $wallet)->first()->cash;
    }
}
