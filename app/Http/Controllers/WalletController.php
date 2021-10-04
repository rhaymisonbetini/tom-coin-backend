<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use WalletRepository;

class WalletController extends Controller
{
    protected $WalletRepository;

    public function __construct(WalletRepository $WalletRepository)
    {
        $this->WalletRepository = $WalletRepository;
    }
}
