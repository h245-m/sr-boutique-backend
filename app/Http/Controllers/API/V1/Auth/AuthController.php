<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CheckOTPRequest;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Mail\RegisterMail;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{

    public function register(RegisterUserRequest $request)
    {
        $otp = rand(100000, 999999);

        $data = $request->validated();

        $data['otp'] = $otp;

        $user = User::create($data);
        
        $user->assignRole('client');

        Mail::to($user->email)->send(new RegisterMail($otp));

        $expiresAt = new DateTime('+7 minutes');

        $user->token = $user->createToken(env('SANCTUM_TOKEN'), ['*'], $expiresAt)->plainTextToken;
        
        return $this->respondCreated($user, 'Registered successfully');

    }


    public function login(LoginUserRequest $request)
    {
        $fields = $request->validated();

        $user = User::where('email', $fields['email'])->first();

        if (! $user || ! Hash::check(request()->post('password'), $user->password)) {
            return $this->respondError('Bad credentials.');
        }

        if (! $user->email_verified_at) {
            return $this->respondError('Please verify your email first');
        }

        $token = $user->createToken(env("SANCTUM_TOKEN"))->plainTextToken;

        $user->token = $token;
        $user->role = $user->getRoleNames()[0];
        unset($user->roles);
        
        return $this->respondOk([
            "user" => $user
        ] , 'Login successfully');

    }


    public function check_otp(CheckOTPRequest $request)
    {
        $data = $request->validated();

        $user = $request->user;

        if ($data['otp'] != $user->otp) {
           return $this->respondError('Wrong OTP');
        }
       
        $user->update([
            'otp' => null,
            'email_verified_at' => now()
        ]);

        return $this->respondNoContent();
    }

    public function logout(Request $request){
        
        $request->user->currentAccessToken()->delete();
        return $this->respondNoContent();
    }

    public function logoutAllDevices(Request $request){
        
        $request->user->tokens()->delete();
        return $this->respondNoContent();
    }

}
    
