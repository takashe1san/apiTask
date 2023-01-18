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
        $rules = [
            'name'     => 'required',
            'email'    => ['email', 'required', 'unique:users'],
            'password' => 'required',
            'address'  => 'nullable',
            'city'     => 'required',
        ];

        $request->validate($rules);

        $user = new User([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'address'  => $request->address,
            'city'     => $request->city,
            'type'     => 'user',
        ]);

        if($user->save()){

            VerificationController::sendVerification($user);

            return apiResponse(1, $user);
        }else{
            return apiResponse(0,'Account doesn\'t created!!');
        }
    }

    public function login(Request $request)
    {
        if(!$token = auth()->attempt($request->only(['email', 'password']))){
            return response()->json([
                'seccess' => false,
                'message' => 'Unauthorized'
            ], 401);
        }
        return $this->respondWithToken($token);
    }

    public function logout(){
        if(auth()->logout())
            apiResponse(1, 'logged out');
    }

    protected function respondWithToken($token){
        return apiResponse(1, [
                'access_token' => $token,
                'token_type'   => 'beared',
                'expires_in'   => env('JWT_TTL', 60)*60
        ]);
    }
}
