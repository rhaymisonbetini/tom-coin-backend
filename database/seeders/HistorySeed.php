<?php

namespace Database\Seeders;

use App\Models\TomCoinHistory;
use Carbon\Carbon;
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

        $initialDate = Carbon::parse('2021-10-01');
        (int) $diference = Carbon::parse(Carbon::now())->diffInDays($initialDate);

        for ($i = 1; $i < $diference + 1; $i++) {
            $tomCoinHistory = new TomCoinHistory();
            $tomCoinHistory->cash = mt_rand(1, 2);
            $tomCoinHistory->date = $i . '/10' . '/2021';
            $tomCoinHistory->created_at = Carbon::parse('2021-10-' . $i);
            $tomCoinHistory->save();
        }
    }
}
