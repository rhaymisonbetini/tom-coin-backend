<?php

namespace App\Repositories;

use App\Models\Transactions;

class TransactionsRepository
{

    public function getUserTransactions($userId): mixed
    {
        return Transactions::where('to_user', $userId)
            ->orWhere('from_user', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function createTransaction($from, $to, $cash): mixed
    {
        $transaction = new Transactions();
        $transaction->from_user = $from;
        $transaction->to_user = $to;
        $transaction->cash = $cash;
        $transaction->save();
        return $transaction;
    }
}
