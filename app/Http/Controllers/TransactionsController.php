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
     * return error response.
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
                $transactions->load('toUser', 'fromUser');
            }
            return  response()->json($transactions, 200);
        } catch (\Exception $e) {
            return response()->json($e, 400);
        }
    }
}
