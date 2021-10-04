<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PoolingRepository;

class PoolingController extends Controller
{
    protected $PoolingRepository;

    public function __construct(PoolingRepository $PoolingRepository)
    {
        $this->PoolingRepository = $PoolingRepository;
    }
}
