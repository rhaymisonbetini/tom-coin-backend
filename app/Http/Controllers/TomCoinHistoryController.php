<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TomCoinHistoryRepository;

class TomCoinHistoryController extends Controller
{
    protected $TomCoinHistoryRepository;

    public function __construct(TomCoinHistoryRepository $TomCoinHistoryRepository)
    {
        $this->TomCoinHistoryRepository = $TomCoinHistoryRepository;
    }
}
