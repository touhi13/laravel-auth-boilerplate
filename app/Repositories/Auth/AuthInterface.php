<?php

namespace App\Repositories\Auth;

interface AuthInterface
{
    public function login($data);
    public function register($data);
    public function getUserByEmail($email);
    public function getUser($id);
    public function emailValidate($data);
    public function otpVerification($data);
    public function resetPassword($data);
}
