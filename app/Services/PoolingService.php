<?php

namespace App\Services;

use App\Repositories\PoolingRepository;
use Illuminate\Support\Facades\Http;
use App\Services\PythonService;

class PoolingService
{

    private $pythonService;
    private $poolingRepository;
    private $totalTransactionInBlock = 5;

    public function __construct(PythonService $pythonService, PoolingRepository $poolingRepository)
    {
        $this->pythonService = $pythonService;
        $this->poolingRepository = $poolingRepository;
    }


    public function createBlockChainByPooling(): string
    {

        $pooling = $this->poolingRepository->takeFiveTransactions($this->totalTransactionInBlock);

        if (!$pooling &&  count($pooling) < 5) {

            return 'NOT_MINED';
        } else {
            $mining = Http::get($this->pythonService->minBlock());
            if ($mining->status() == 200) {

                $this->createPooling($pooling);

                $newPooling = $this->poolingRepository->takeFiveTransactions($this->totalTransactionInBlock);

                if ($newPooling && count($newPooling)  == 5) {
                    $this->createBlockChainByPooling();
                } else {
                    return 'MINED';
                }
            } else {
                return 'NOT_MINED';
            }
        }
    }

    public function populateBlockChain(): string
    {

        $blockChain = Http::get($this->pythonService->getChain());
        $blockChain = $blockChain->json();
        $lastBlock =  end($blockChain['chain']);
        $transactions = $lastBlock['transactions'];
        $restToLockBlock = $this->totalTransactionInBlock - count($transactions);
        $pooling = $this->poolingRepository->takeFiveTransactions($restToLockBlock);
        $poolingResponse = $this->createPooling($pooling);
        return $poolingResponse;
    }

    public function createPooling($pooling): string
    {
        $pooling->load(
            'transactions',
            'transactions.toUser',
            'transactions.toUser.wallets',
            'transactions.fromUser',
            'transactions.fromUser.wallets'
        );

        foreach ($pooling as $transaction) {
            Http::post($this->pythonService->addTransaction(), [
                'sender' => $transaction->transactions->toUser->wallets->public_key,
                'receiver' =>  $transaction->transactions->fromUser->wallets->public_key,
                'amount' => $transaction->transactions->cash,
            ]);
            $transaction->delete();
        }
        return 'CREATED';
    }
}
