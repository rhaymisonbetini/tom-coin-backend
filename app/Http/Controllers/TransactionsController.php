<?php

namespace App\Http\Controllers;

use App\Repositories\TransactionsRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    private $transactionsRepository;
    private $userRepository;

    public function __construct(
        TransactionsRepository $transactionsRepository,
        UserRepository $userRepository,
    ) {
        $this->transactionsRepository = $transactionsRepository;
        $this->userRepository = $userRepository;
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
}
