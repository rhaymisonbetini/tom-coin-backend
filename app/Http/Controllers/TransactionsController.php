<?php

namespace App\Http\Controllers;

use App\Repositories\TransactionsRepository;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    protected $TransactionsRepository;

    public function __construct(TransactionsRepository $TransactionsRepository)
    {
        $this->TransactionsRepository = $TransactionsRepository;
    }
}
