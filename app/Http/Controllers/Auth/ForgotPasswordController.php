<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendResetPasswordEmail;
use App\Models\PasswordReset;
use App\Rules\ValidationRecaptcha;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;


class ForgotPasswordController extends Controller
{
    public function forgot(Request $request) {
        $validator =  Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()
            ], 400);
        }

        $user = User::where('email', $request->email)->first();

        if(!isset($user)){
            return response()->json([
                'error' => ['error' =>   ['This email does not match our records.']]
            ], 400);
        }

        if (!$user->email_verified) {
            return response()->json([
                'error' => ['error' =>   ['Your account is not activated. Please activate it first.']]
            ], 400);
        } else {
            $passwordReset = PasswordReset::create([
                'token' =>  sha1(time()),
                'email' => $request->email,
            ]);

            if ($user) {
                $data = [
                    'firstName' =>  $user->first_name,
                    'token'  =>    $passwordReset->token,
                ];
                $email = new SendResetPasswordEmail($data);
                Mail::to($user->email)->send($email);
            }

            return response()->json(['success'=> 'We are sent reset link in you email address'], 200);
        }

    }

    public function resetPassword(Request $request)
    {

        Validator::make($request->all(), [
            'token' => 'required|string',
            'password'   => 'required|min:6|confirmed'
        ]);

        $passwordReset = PasswordReset::where('token', $request->token)->first();

        if(!$passwordReset) {
            return response()->json([
                'error' => ['error' =>   ['This token is expired']]
            ], 400);
        }

        User::where('email', $passwordReset->email)->update(['password' => Hash::make($request->password)]);

        PasswordReset::where('token', $request->token)->delete();

        return response()->json(['success'], 200);
    }

}
