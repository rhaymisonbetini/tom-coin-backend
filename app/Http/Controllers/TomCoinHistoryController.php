<?php

namespace App\Http\Controllers;

use App\Repositories\TomCoinHistoryRepository;
use Illuminate\Http\Request;

class TomCoinHistoryController extends Controller
{
    protected $TomCoinHistoryRepository;

    public function __construct(TomCoinHistoryRepository $TomCoinHistoryRepository)
    {
        $this->TomCoinHistoryRepository = $TomCoinHistoryRepository;
    }
}
