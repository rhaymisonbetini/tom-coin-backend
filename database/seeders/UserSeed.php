<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->name = Str::random(10);
            $user->email = Str::random(10) . '@gmail.com';
            $user->password =  Hash::make(123456);
            $user->save();

            $wallet = new Wallet();
            $privateKey = hash('sha256', Str::random(10));
            $publicKey  = hash('sha256', $privateKey);
            $wallet->id_user = $user->id;
            $wallet->private_key = $privateKey;
            $wallet->public_key = $publicKey;
            $wallet->cash = mt_rand(1, 100);
            $wallet->save();
        }
    }
}
