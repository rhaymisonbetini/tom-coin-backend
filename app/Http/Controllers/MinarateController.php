<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Repositories\PoolingRepository;
use App\Repositories\WalletRepository;
use App\Services\PoolingService;
use App\Services\PythonService;
use App\Services\WalletService;
use Illuminate\Support\Facades\Http;

class MinarateController extends Controller
{

    private $walletRepository;
    private $pythonService;
    private $poolingService;
    private $walletService;

    public function __construct(
        WalletRepository $walletRepository,
        PoolingRepository $poolingRepository,
        PythonService $pythonService,
        PoolingService $poolingService,
        WalletService $walletService,
    ) {
        $this->walletRepository = $walletRepository;
        $this->poolingRepository = $poolingRepository;
        $this->pythonService = $pythonService;
        $this->poolingService = $poolingService;
        $this->walletService = $walletService;
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function createNewBlock()
    {
        try {

            $wallet = Wallet::inRandomOrder()->first()->public_key;
            $response =  Http::post($this->pythonService->createNewBlock(), [
                'USER_EDDEN' => $wallet
            ]);

            if ($response->status() == 201) {
                $this->poolingService->populateBlockChain();
                $this->walletRepository->updateUserMinerate($wallet);
                $response = $this->walletService->calculateActualValue($wallet);
                return response()->json($response, 200);
            } else {
                return response()->json('Erro_block_chain_minerate', $response->status());
            }
        } catch (\Exception $e) {
            return response()->json($e, 400);
        }
    }
}
