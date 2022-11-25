<?php

namespace App\Http\Controllers\Auth;

use App\Mail\VerificationChangedPassword;
use App\Mail\VerificationEmail;
use App\Mail\VerificationEmailChanged;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\PasswordReset;

use App\Http\Controllers\Controller;
use App\Rules\ValidationRecaptcha;

class AuthController extends Controller
{

    public function __construct()
    {
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            //'recaptchaToken' => ['required', new ValidationRecaptcha()]
        ]);
        
        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);

        // dd( $token );

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ]);
        }

        $user = Auth::user();
        return response()->json([
                'status' => 'success',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);

    }

    public function register(Request $request){

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $isEmailAlreadyInUse = User::where(['email' => $request->email])->first() != null;
        $isUsernameAlreadyInUse = User::where(['name' => $request->name])->first() != null;
        
        if ($isEmailAlreadyInUse) {
            return response()->json([
                'status' => 'error',
                'message' => 'This email is already in use.',
            ]);
        }

        if ($isUsernameAlreadyInUse) {
            return response()->json([
                'status' => 'error',
                'message' => 'This Username is already in use.',
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        // try
        // {
        //     Mail::to($user->email)->send(new VerificationEmail($user));
        // }
        // catch (Exception $e)
        // {
            
        // }

        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    public function update(Request $request)
    {
        
        $request->validate([
            'oldname' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email',
        ]);

        
        if (!User::where(['name' => $request->oldname])->first()) {
            return response()->json([
                'status' => 'error',
                'message' => 'This user does not exist!',
            ]);
        }

        $isEmailAlreadyInUse = User::where(['email' => $request->email])->first() != null;
        $isUsernameAlreadyInUse = User::where(['name' => $request->name])->first() != null;
        
        // if ($isEmailAlreadyInUse) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'This email is already in use.',
        //     ]);
        // }

        if ($isUsernameAlreadyInUse) {
            return response()->json([
                'status' => 'error',
                'message' => 'This Username is already in use.',
            ]);
        }

        User::where('name', $request->oldname)->update(['name' => $request->name, 'email' => $request->email]);

        $user = User::where('name', $request->name)->first();
        return response()->json([
                'status' => 'success',
                'user' => $user
            ]);
    }

    public function resetpwd(Request $request)
    {
        Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
            'newpassword'   => 'required|min:6|confirmed'
        ]);

        if (!User::where(['email' => $request->email])->first()) {
            return response()->json([
                'status' => 'error',
                'message' => 'This user does not exist!',
            ]);
        }
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ]);
        }
        User::where('email', $request->email)->update(['password' => Hash::make($request->newpassword)]);
        $user = User::where('email', $request->email)->first();
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }
}