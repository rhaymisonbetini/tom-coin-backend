<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MinarateController extends Controller
{
    /**
     * @var string
     */
    protected $getChain = 'http://127.0.0.1:5000/get_chain';
    /**
     * @var string
     */
    protected $createNewBlock = 'http://127.0.0.1:5000/create-new-block';

    public function getWeaponsToMine()
    {
        try {
            $blockChain = Http::get($this->getChain);
            $blockChain = $blockChain->json();
            $lastBlock =  end($blockChain['chain'])['proof'];
            return response()->json($lastBlock, 200);
        } catch (\Exception $e) {
            return response()->json($e, 400);
        }
    }

    public function createNewBlock($newProof)
    {
        try {
            $user = Wallet::inRandomOrder()->first()->public_key;
            $response =  Http::post($this->createNewBlock, [
                'new_prof' => $newProof,
                'USER_EDDEN' => $user
            ]);
            $responseBlockChain =  $response->json();
            return response()->json($responseBlockChain, 200);

        } catch (\Exception $e) {
            return response()->json($e, 400);
        }
    }
}
