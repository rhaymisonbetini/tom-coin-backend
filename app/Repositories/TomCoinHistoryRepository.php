<?php

namespace App\Repositories;

use App\Models\TomCoinHistory;

class TomCoinHistoryRepository
{

    public function lastValue(): float
    {
        return TomCoinHistory::orderBy('id', 'desc')->first()->cash;
    }
}
