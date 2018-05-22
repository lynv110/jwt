<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(){
        $user = User::create([
            'name' => request()->input('name'),
            'email' => request()->input('email'),
            'password' => bcrypt(request()->input('password')),
        ]);

        return response()->json([
           'status' => 'success',
           'data' => $user
        ]);
    }

    public function login(Request $request){
        $credentials = $request->only(['email', 'password']);

        if (!$token = JWTAuth::attempt($credentials)) {
            return response([
                'status' => 'error',
                'error' => 'invalid.credentials',
                'msg' => 'Invalid Credentials.'
            ], 400);
        }

        $token = null;
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response([
                    'status' => 'error',
                    'error' => 'invalid.credentials',
                    'msg' => 'Invalid Credentials.'
                ], 400);
            }
        } catch (JWTException $e) {
            return response()->json([
                'status' => 'error',
                'error' => 'failed_to_create_token',
            ], 500);
        }

        return response([
            'status' => 'success',
            'token' => $token
        ]);

    }

    public function info(Request $request){
        $user = JWTAuth::toUser($request->input('token'));

        return response([
            'status' => 'success',
            'user' => $user
        ]);
    }
}
