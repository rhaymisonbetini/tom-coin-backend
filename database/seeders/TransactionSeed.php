<?php

namespace Database\Seeders;

use App\Models\Transactions;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransactionSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 200; $i++) {
            $this->createTransaction();
        }
    }

    public function createTransaction()
    {
        $userTo = User::inRandomOrder()->first()->id;
        $fromUser = User::inRandomOrder()->first()->id;

        if ($userTo == $fromUser) {
            $this->createTransaction();
            return;
        }

        $transaction = new Transactions();
        $transaction->to_user = $userTo;
        $transaction->from_user = $fromUser;
        $transaction->cash = mt_rand(1, 5);
        $transaction->save();
    }
}
