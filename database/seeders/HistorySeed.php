<?php

namespace Database\Seeders;

use App\Models\TomCoinHistory;
use Illuminate\Database\Seeder;

class HistorySeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $month = ['05', '06', '07', '08', '09', '10'];
        for ($i = 0; $i < count($month); $i++) {

            $tomCoinHistory = new TomCoinHistory();
            $tomCoinHistory->cash = mt_rand(1, 10);
            $tomCoinHistory->date = '01/' . $month[$i] . '/2021';
            $tomCoinHistory->save();
        }
    }
}
