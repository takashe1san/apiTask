<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api', ['except' => ['login', 'signup']]);
    }

    public function signup(Request $request){
        $roles = [
            'name'     => 'required',
            'email'    => ['email', 'required', 'unique:users'],
            'password' => 'required',
            'address'  => 'nullable',
            'city'     => 'required',
        ];

        $request->validate($roles);

        if($user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'address'  => $request->address,
            'city'     => $request->city,
            'type'     => 'user',
        ])){

            event(new Registered($user));

            return response()->json(['msg' => 'Account created!!']);
            
        }else{
            return response()->json(['msg' => 'Something went wrong!!!']);
        }
    }

    public function login(Request $request){
        $credentials = $request->only(['email', 'password']);
        if(!$token = auth()->attempt($credentials)){
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    public function logout(){
        auth()->logout();
    }

    protected function respondWithToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'beared',
            'expires_in'   => env('JWT_TTL', 60)*60
        ]);
    }
}
