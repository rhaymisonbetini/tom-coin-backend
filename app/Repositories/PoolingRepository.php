<?php

namespace App\Repositories;

use App\Models\Pooling;

class PoolingRepository
{
    public function takeFiveTransactions($takes): mixed 
    {
        return Pooling::take($takes)->orderBy('id', 'desc')->get();
    }
}
