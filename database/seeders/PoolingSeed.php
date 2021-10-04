<?php

namespace Database\Seeders;

use App\Models\Pooling;
use App\Models\Transactions;
use Illuminate\Database\Seeder;

class PoolingSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $transactions = Transactions::orderBy('id','desc')->take('25')->get();
        foreach ($transactions as $transaction){
            $pooling = new Pooling();
            $pooling->transaction_id = $transaction->id;
            $pooling->save();
        }
    }
}
