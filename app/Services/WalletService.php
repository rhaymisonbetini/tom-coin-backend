<?php

namespace App\Services;

use App\Repositories\TomCoinHistoryRepository;
use App\Repositories\WalletRepository;

class WalletService
{

    private $walletRepository;
    private $tomCoinHistoryRepository;

    public function __construct(
        WalletRepository $walletRepository,
        TomCoinHistoryRepository $tomCoinHistoryRepository,

    ) {
        $this->walletRepository = $walletRepository;
        $this->tomCoinHistoryRepository = $tomCoinHistoryRepository;
    }


    public function calculateActualValue($wallet): array
    {
        (float) $lastValue = $this->tomCoinHistoryRepository->lastValue();
        (float) $userWallet = $this->walletRepository->getUserCashByPublicKey($wallet);
        (float) $userValue = $userWallet * $lastValue;
        (array)  $response = [
            'user_valuer' =>  $userValue,
            'tom_coin_cotation' => $lastValue,
            'formated_value' => 'R$ ' . number_format($userValue, 2, ",", ".")
        ];
        return $response;
    }
}
