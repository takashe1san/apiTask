<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = User::find($request->route('id'));

        if ($user->hasVerifiedEmail()) {
            return 'Your email already verified ^_^';
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return 'Email verified successfully :)';
    }

    public function resendVerification(){

        $user = auth()->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json(['msg' => 'Your email already verified ^_^']);
        }

        $user->sendEmailVerificationNotification();
        
        return response()->json(['msg' => 'Verification link sent!']);
    }
}
