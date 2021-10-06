<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PoolingRepository;

class PoolingController extends Controller
{
    protected $PoolingRepository;

    public function __construct(PoolingRepository $PoolingRepository)
    {
        $this->PoolingRepository = $PoolingRepository;
    }


    public function createInicialBlockChain()
    {
        try {
            $blockChain = $this->PoolingRepository->createBlockChainByPooling();
            return response()->json($blockChain, 200);
        } catch (\Exception $e) {
            return response()->json($e, 400);
        }
    }
}
