<?php

namespace App\Repositories;

use App\Models\Pooling;
use Illuminate\Support\Facades\Http;

class PoolingRepository
{

    /**
     * @var string
     */
    protected $getChain = 'http://127.0.0.1:5000/get_chain';
    /**
     * @var string
     */
    protected  $minBlock = 'http://127.0.0.1:5000/mine_block';
    /**
     * @var string
     */
    protected  $addTransaction = 'http://127.0.0.1:5000/add_transaction';


    public function createBlockChainByPooling()
    {

        $pooling = Pooling::take(5)->get();

        if (!$pooling &&  count($pooling) < 5) {

            return 'NOT_MINED';
        } else {

            $mining = Http::get($this->minBlock);
            if ($mining->status() == 200) {

                $this->createPooling($pooling);

                $newPooling = Pooling::take(5)->get();

                if ($newPooling && count($pooling)  == 5) {
                    $this->createBlockChainByPooling();
                }else{
                    return 'MINED';
                }

            } else {
                return 'NOT_MINED';
            }
        }
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
            Http::post($this->addTransaction, [
                'sender' => $transaction->transactions->toUser->wallets->public_key,
                'receiver' =>  $transaction->transactions->fromUser->wallets->public_key,
                'amount' => $transaction->transactions->cash,
            ]);
            $transaction->delete();
        }
        return 'CREATED';
    }
}
