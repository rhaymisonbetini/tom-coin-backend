<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MinarateController extends Controller
{
    /**
     * @var string
     */
    protected $getChain = 'http://127.0.0.1:5000/get_chain';

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
}
