<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class AuthController extends Controller
{

    protected $UserRepository;

    public function __construct(UserRepository $UserRepository)
    {
        $this->UserRepository = $UserRepository;
    }

    /**
     * return response.
     *
     * @return \Illuminate\Http\Response
     */

    public function login(Request $request)
    {
        try {

            $request->validate([
                'email' => 'required|string',
                'password' => 'required|string'
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response([
                    'message' => 'UNAUTHORIZED'
                ], 401);
            }

            $token = $user->createToken(Str::random(10))->plainTextToken;

            $response = [
                'user' => $user,
                'token' => $token
            ];

            Auth::login($user);
            return response()->json($response, 201);
        } catch (\Exception $e) {
            return response()->json($e, 400);
        }
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */

    public function logout(Request $request)
    {
        try {

            $user = User::where('email', '=', $request->email)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response([
                    'message' => 'UNAUTHORIZED'
                ], 401);
            }
            $this->revokeTokens($user);
            Auth::logout();
        } catch (\Exception $e) {
            return response()->json($e, 400);
        }
    }

    public function revokeTokens($user): bool
    {
        $user->tokens()->delete();
        return true;
    }
}
