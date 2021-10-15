<?php

namespace App\Http\Controllers;

use App\Repositories\PoolingRepository;
use App\Repositories\TransactionsRepository;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;
use App\Services\PoolingService;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    private $transactionsRepository;
    private $userRepository;
    private $walletRepository;
    private $pollingRepository;
    private $poolingService;

    public function __construct(
        TransactionsRepository $transactionsRepository,
        UserRepository $userRepository,
        WalletRepository $walletRepository,
        PoolingRepository $pollingRepository,
        PoolingService $poolingService
    ) {
        $this->transactionsRepository = $transactionsRepository;
        $this->userRepository = $userRepository;
        $this->walletRepository = $walletRepository;
        $this->pollingRepository = $pollingRepository;
        $this->poolingService = $poolingService;
    }


    /**
     * return response.
     *
     * @return \Illuminate\Http\Response
     */

    public function userTransactions($email)
    {

        try {
            $user = $this->userRepository->getUserByEmail($email);
            if (!$user) {
                return response()->json(['message' => 'USER_NOT_FOUND'], 404);
            }
            $transactions = $this->transactionsRepository->getUserTransactions($user->id);
            if ($transactions) {
                $transactions->load('toUser', 'toUser.wallets', 'fromUser', 'fromUser.wallets');
            }
            (array) $arrayTransactions = [];
            foreach ($transactions as $transaction) {
                array_push($arrayTransactions, [
                    'from' => substr($transaction->fromUser->wallets->public_key, 0, 50) . '...',
                    'to' => substr($transaction->toUser->wallets->public_key, 0, 50) . '...',
                    'cash' => $transaction->cash,
                    'date' => date_format($transaction->created_at, 'd-m-Y H:i:s')
                ]);
            }
            return  response()->json($arrayTransactions, 200);
        } catch (\Exception $e) {
            return response()->json($e, 400);
        }
    }

    /**
     * return response.
     *
     * @return \Illuminate\Http\Response
     */

    public function transactions(Request $request)
    {
        try {
            $user = $this->userRepository->getUserByEmail($request->email);
            $toWallet = $this->walletRepository->getWalletByPublicKey($request->from_key);
            if ($user->wallets->public_key ==  hash('sha256', $user->wallets->private_key) || !$toWallet) {
                $transaction = $this->transactionsRepository->createTransaction($user->id, $toWallet->user->id, $request->cash);
                $this->walletRepository->updateUserWalletTransfer($toWallet->user->id, $request->cash);
                $this->pollingRepository->createPooling($transaction->id);
                $this->poolingService->populateBlockChain();
                return response()->json(['message' => 'TRANSACTION_SUCCESS'], 201);
            } else {
                return response()->json(['message' => 'UNAUTHORIZED'], 401);
            }
        } catch (\Exception $e) {
            return response()->json($e, 400);
        }
    }
}
