<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PoolingController;
use App\Http\Controllers\MinarateController;
use App\Http\Controllers\TomCoinHistoryController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\WalletController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
|
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//create initial blockchain genesis
Route::get('/create-initial-block-chain', [PoolingController::class, 'createInicialBlockChain']);

Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user-wallet-information/{email}', [WalletController::class, 'userWallet']);
    Route::get('transactions/{email}', [TransactionsController::class, 'userTransactions']);
    Route::post('transaction', [TransactionsController::class, 'transactions']);
    Route::get('/create-block', [MinarateController::class, 'createNewBlock']);
    Route::get('/blockchain', [MinarateController::class, 'getBlockChainInformations']);
    Route::get('machine-learning', [TomCoinHistoryController::class, 'MachineTomPredict']);
    Route::get('tom-coin-history', [TomCoinHistoryController::class, 'tomCoinHistory']);
});
