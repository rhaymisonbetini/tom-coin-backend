<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;
use App\Services\WalletService;

class WalletController extends Controller
{
    private $walletRepository;
    private $userRepository;
    private $walletService;

    public function __construct(
        WalletRepository $walletRepository,
        UserRepository $userRepository,
        WalletService $walletService
    ) {
        $this->walletRepository = $walletRepository;
        $this->userRepository = $userRepository;
        $this->walletService = $walletService;
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */

    public function userWallet($email)
    {
        try {
            $user = $this->userRepository->getUserByEmail($email);
            if (!$user) {
                return response()->json(['message' => 'USER_NOT_FOUND'], 404);
            }

            $wallet = $this->walletRepository->getUserWallet($user->id);

            if (!$wallet) {
                return response()->json(['message' => 'WALLET_NOT_FIND'], 404);
            }

            (array) $response  = $this->walletService->calculateActualValue($wallet->public_key);
            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json($e, 400);
        }
    }
}
