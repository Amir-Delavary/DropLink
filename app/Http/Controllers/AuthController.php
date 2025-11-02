<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function register(RegisterRequest $request){
        $credentials = $request->validated();
        $user = User::create(["email" => $credentials["email"], "password" => Hash::make($credentials["password"])]);
        $token = auth()->login($user);
        return response()->json(["token" => $token]);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $token = auth()->attempt($credentials);
        return response()->json(["token" => $token]);
    }

    public function refresh()
    {
        $token = auth()->refresh();
        return response()->json(["token" => $token]);
    }
    public function me()
    {
        return response()->json(auth()->user());
    }
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }
}
