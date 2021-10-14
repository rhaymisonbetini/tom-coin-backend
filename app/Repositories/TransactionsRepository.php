<?php

namespace App\Repositories;

use App\Models\Transactions;

class TransactionsRepository
{

    public function getUserTransactions($userId): mixed
    {
        return Transactions::where('to_user', $userId)->orWhere('from_user', $userId)->get();
    }
}
