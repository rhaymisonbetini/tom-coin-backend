<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use UserRepository;

class AuthController extends Controller
{

    protected $UserRepository;

    public function __construct(UserRepository $UserRepository)
    {
        $this->UserRepository = $UserRepository;
    }
}
