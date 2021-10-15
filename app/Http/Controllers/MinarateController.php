<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Repositories\PoolingRepository;
use App\Repositories\WalletRepository;
use App\Services\PoolingService;
use App\Services\PythonService;
use App\Services\WalletService;
use Illuminate\Support\Facades\Auth;
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
     * return response.
     *
     * @return \Illuminate\Http\Response
     */
    public function createNewBlock()
    {
        try {

            $user = Auth::user();

            $wallet = $this->walletRepository->getUserWallet($user->id);

            $response =  Http::post($this->pythonService->createNewBlock(), [
                'USER_EDDEN' => $wallet->public_key
            ]);

            if ($response->status() == 201) {
                $this->poolingService->populateBlockChain();
                $this->walletRepository->updateUserMinerate($wallet->public_key);
                $response = $this->walletService->calculateActualValue($wallet->public_key);
                return response()->json($response, 200);
            } else {
                return response()->json('Erro_block_chain_minerate', $response->status());
            }
        } catch (\Exception $e) {
            return response()->json($e, 400);
        }
    }

    public function getBlockChainInformations()
    {
        try {

            $blockChain = Http::get($this->pythonService->getChain());
            $blockChain = $blockChain->json();
            
            (int) $totalBlock = count($blockChain['chain']);
            (int) $totalTransactions = 0;

            foreach ($blockChain['chain'] as $block) {
                $totalTransactions += count($block['transactions']);
            }

            (array) $response = [
                'block' => $totalBlock,
                'transactions' => $totalTransactions,
            ];
            return response()->json($response,200);

        } catch (\Exception $e) {
            return response()->json($e, 400);
        }
    }
}
