<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TransactionsRepository;

class TransactionsController extends Controller
{
    protected $TransactionsRepository;

    public function __construct(TransactionsRepository $TransactionsRepository)
    {
        $this->TransactionsRepository = $TransactionsRepository;
    }
}
