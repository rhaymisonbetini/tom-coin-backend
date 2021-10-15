<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PoolingRepository;
use App\Services\PoolingService;

class PoolingController extends Controller
{
    protected $poolingService;

    public function __construct(PoolingService $poolingService)
    {
        $this->poolingService = $poolingService;
    }


     /**
    * return response.
     *
     * @return \Illuminate\Http\Response
     */
    public function createInicialBlockChain()
    {
        try {
            $blockChain = $this->poolingService->createBlockChainByPooling();
            return response()->json($blockChain, 200);
        } catch (\Exception $e) {
            return response()->json($e, 400);
        }
    }
}
