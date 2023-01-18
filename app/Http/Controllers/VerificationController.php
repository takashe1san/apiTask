<?php

namespace App\Http\Controllers;

use App\Models\EmailVerificationTokens;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class VerificationController extends Controller
{
    public function verify($id, $token)
    {
        $user = User::find($id);
        if ($user->hasVerifiedEmail()) {
            return 'Your email already verified ^_^';
        }

        if(!$token = EmailVerificationTokens::where('user_id', $id)->first()){
            return 'your email verification request is crashed, please make a new one';
        }

        if($this->checkIfExpire($token)){
            return 'this link is expire, please make new one';
        }

        if($user->markEmailAsVerified()){
            $token->delete();
            return 'Email verified successfully :)';
        }
        
        return 'Email verification failed, please try again';
    }

    public static function sendVerification(User $user){

        if($user->hasVerifiedEmail()){
            return apiResponse(0, 'Your Email is already Verified!!');
        }
        $token = new EmailVerificationTokens([
            'user_id' => $user->id,
            'token'   =>  Str::random(40),
        ]);
        
        if(!$token->save()){
            return apiResponse(0, 'verification token is not created!!');
        }

        $verificationLink = url('/api/email/verify/'.$user->id.'/'.$token->token);
        
        $name = $user->name;
        $email = $user->email;

        if(Mail::send('email.verification', ['verificationLink' => $verificationLink], function ($message) use ($name, $email) {
            $message->from('sender@example.com', 'Sender Name');
            $message->to($email, $name)->subject('Verify your email address');
        })){
            return apiResponse(1, 'Verification mail send, check your Email');
        }else{
            return apiResponse(0, 'Verification mail is not send, please try again!!');
        }
        
        
    }

    public function resendVerification(Request $request){

        if(!$user = User::where('email', $request->email)->first())
        {
            return apiResponse(0, 'this email is not registered!!');
        }
 
        if($token = EmailVerificationTokens::where('user_id', $user->id)->first()){
            $token->delete();
        }
        
        return $this->sendVerification($user);
    }

    private static function checkIfExpire(EmailVerificationTokens $token){
        $expiresDate = Carbon::parse($token->expires_at);
        if($expiresDate->isPast()){
            return true;
        }
        return false;
    }
}
