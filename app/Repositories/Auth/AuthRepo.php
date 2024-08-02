<?php
namespace App\Repositories\Auth;

use App\Models\User;
use App\Repositories\Auth\AuthInterface;
use Ichtrojan\Otp\Otp;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthRepo implements AuthInterface
{
    public function login($data)
    {
        $credentials = $data->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return false;
        }

        return $token;
    }

    public function register($data)
    {
        $user           = new User();
        $user->name     = $data->name;
        $user->email    = $data->email;
        $user->password = $data->password;
        $user->save();
        return $user;
    }

    public function getUserByEmail($email)
    {
        $user = User::where('email', $email)->first();
        return $user;
    }
    public function getUser($id)
    {
        $user = User::find($id);
        return $user;
    }

    public function emailValidate($data)
    {
        $response = (new Otp)->validate($data['email'], $data['otp']);

        if ($response->status) {
            $user                    = User::query()->where('email', $data['email'])->first();
            $user->email_verified_at = now();
            $user->save();
        }

        return $response->status;
    }

    public function otpVerification($data)
    {
        $response = (new Otp)->validate($data['email'], $data['otp']);

        if ($response->status) {
            $user                    = User::query()->where('email', $data['email'])->first();
            $user->email_verified_at = now();
            $user->save();
        }
        return $response->status;
    }

    public function resetPassword($data)
    {
        $user           = User::query()->where('email', $data['email'])->first();
        $user->password = bcrypt($data['password']);
        $user->save();

        return $user;
    }
}
