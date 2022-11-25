<?php

namespace App\Http\Controllers\Auth;

use App\Models\EmailPassword;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Tymon\JWTAuth\Facades\JWTAuth;

class VerifyController extends Controller
{
    public function verifyEmail($token = null)
    {
        if($token == null) {
            return redirect()->route('login');
        }

        $user = User::where('email_verification_token',$token)->first();

        if($user == null ){
            return redirect()->to('login');
        }

        $user->update([
            'email_verified' => 1,
            'email_verified_at' => Carbon::now(),
            'email_verification_token' => ''
        ]);

        $token =  JWTAuth::fromUser($user);

        $user = User::findOrFail($user->id);

        return response()->json([
            'message' => 'success',
            'token' => $token,
            'user' => $user,
        ], 200);

    }

    public function verifyChangedPassword($token = null)
    {
        if($token == null) {
            return redirect()->route('login');
        }

        $emailPassword = EmailPassword::where('token',$token)->first();

        if($emailPassword == null ){
            return redirect()->to('login');

        }

        $user = User::where('email', $emailPassword->email)->where('type', 'password')->first();

        $user->update([
            'change_password_verified' => 1
        ]);

        $emailPassword->delete();

        $token =  JWTAuth::fromUser($user);

        $user = User::findOrFail($user->id);

        return response()->json([
            'message' => 'success',
            'token' => $token,
            'user' => $user,
        ], 200);

    }

    public function verifyChangedEmail($token = null)
    {
        if($token == null) {
            return redirect()->route('login');
        }

        $emailPassword = EmailPassword::where('token',$token)->where('type', 'email')->first();

        if($emailPassword == null ){
            return redirect()->to('login');

        }

        $user = User::where('email', $emailPassword->email)->first();

        $user->update([
            'change_email_verified' => 1
        ]);

        $emailPassword->delete();

        $token =  JWTAuth::fromUser($user);

        $user = User::findOrFail($user->id);

        return response()->json([
            'message' => 'success',
            'token' => $token,
            'user' => $user,
        ], 200);
    }

}
