<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Repositories\PoolingRepository;
use App\Repositories\TomCoinHistoryRepository;
use App\Repositories\WalletRepository;
use App\Services\PoolingService;
use App\Services\PythonService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MinarateController extends Controller
{

    private $walletRepository;
    private $poolingRepository;
    private $tomCoinHistoryRepository;
    private $pythonService;
    private $poolingService;

    public function __construct(
        WalletRepository $walletRepository,
        PoolingRepository $poolingRepository,
        TomCoinHistoryRepository $tomCoinHistoryRepository,
        PythonService $pythonService,
        PoolingService $poolingService,
    ) {
        $this->walletRepository = $walletRepository;
        $this->poolingRepository = $poolingRepository;
        $this->tomCoinHistoryRepository = $tomCoinHistoryRepository;
        $this->pythonService = $pythonService;
        $this->poolingService = $poolingService;
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
                $response = $this->calculateActualValue($wallet);
                return response()->json($response, 200);
            }else{
                return response()->json('Erro_block_chain_minerate', $response->status());
            }

        } catch (\Exception $e) {
            return response()->json($e, 400);
        }
    }

    public function calculateActualValue($wallet): array
    {
        $lastValue = $this->tomCoinHistoryRepository->lastValue();
        $userWallet = $this->walletRepository->getUserCashByPublicKey($wallet);
        $response = [
            'user_valuer' =>  $userWallet * $lastValue,
            'tom_coin_cotation' => $lastValue
        ];

        return $response;
    }
}
