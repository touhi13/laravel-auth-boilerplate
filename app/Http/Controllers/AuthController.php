<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegistrationRequest;
use App\Jobs\SendEmail;
use App\Repositories\Auth\AuthInterface;
use App\Traits\ApiResponseTrait;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponseTrait;

    private AuthInterface $repository;

    public function __construct(AuthInterface $repository)
    {
        $this->repository = $repository;
    }

    public function login(Request $request): JsonResponse
    {

        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $token = $this->repository->login($request);

        if (!$token) {
            return $this->ResponseError('Invalid Credentials', null, 'Invalid Credentials', 401);
        }

        $user = $this->repository->getUserByEmail($request->email);

        if (!$user->email_verified_at) {
            return $this->ResponseSuccess([], 'Email not verified', "Your email address is not verified.", 403);
        }

        return $this->ResponseSuccess(['token' => $token, 'user'  => $user,], 'Login Successful', "login Successful", 200);
    }

    public function logout()
    {

        Auth::logout();

        return response()->json([
            'status'  => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function register(UserRegistrationRequest $request): JsonResponse
    {
        $user = $this->repository->register($request);

        if (!$user) {
            return $this->ResponseError('Registration Failed', null, 'Registration Failed', 400);
        }

        $email = $user->email;
        $otp   = (new Otp)->generate($email, 'numeric', 6, 5);

        try {
            $data = [
                'subject'    => "Email Verification",
                'email_body' => view('mails.otp.email_verification', ['email' => $email, 'otp' => $otp->token])->render(),
                'to'         => [$email],
            ];
            SendEmail::dispatch($data);
        } catch (\Throwable $th) {
            //throw $th;
        }

        return $this->ResponseSuccess($user, 'Registration Successful', 200);
    }

    public function emailValidate(Request $request)
    {
        $validatedData = $request->validate([
            'email'    => 'required|email|exists:users,email,email_verified_at,NULL',
            'otp'      => 'required|numeric',
            'password' => 'required|string|min:6',
        ]);

        $validated = $this->repository->emailValidate($validatedData);

        if (!$validated) {
            return $this->ResponseError('Email verification unsuccessful', 401);
        }

        $token = $this->repository->login($request);
        $user  = $this->repository->getUserByEmail($request->email);
        $data  = [
            'token' => $token,
            'user'  => $user,
        ];
        return $this->ResponseSuccess($data, 'Email verification successful', 200);
    }

    public function resendEmailVerificationOtp(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'email' => 'required|email|exists:users,email,email_verified_at,NULL',
        ]);

        $email = $validatedData['email'];
        $otp   = (new Otp)->generate($email, 'numeric', 6, 5);
        $data  = [
            'subject'    => "Amz Alert- OTP Verification",
            'email_body' => view('mails.otp.email_verification', ['email' => $email, 'otp' => $otp->token])->render(),
            'to'         => [$email],
        ];

        SendEmail::dispatch($data);

        return $this->ResponseSuccess([], 'Otp sent', 200);
    }

    public function forgotPasswordOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $email = $request->email;
        $otp   = (new Otp)->generate($email, 'numeric', 6, 5);

        $data = [
            'subject'    => "Amz Alert- Forgot Password Otp",
            'email_body' => view('mails.otp.forgot_password', ['email' => $email, 'otp' => $otp->token])->render(),
            'to'         => [$email],
        ];

        SendEmail::dispatch($data);

        return $this->ResponseSuccess([], 'Otp sent', 200);
    }

    public function otpVerification(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'otp'   => 'required|numeric',
        ]);

        $verified = $this->repository->otpVerification($request);

        if (!$verified) {
            return $this->ResponseError('Otp verification failed', 400);
        }

        // $otp = (new Otp)->generate($request->email, 'numeric', 6, 5);

        return $this->ResponseSuccess(['success' => true], 'Otp verification successful', 200);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        if ($request->input('password') !== $request->input('password_confirmation')) {
            return $this->ResponseError('Password and password confirmation do not match', 400);
        }

        $request->validate([
            'email'                 => 'required|email|exists:users',
            'password'              => 'required|string|min:6',
            'password_confirmation' => 'required|same:password',
            'otp'                   => 'required|numeric',
        ]);

        $verified = $this->repository->otpVerification($request);

        if (!$verified) {
            return $this->ResponseError('Otp verification failed', 400);
        }

        $reset = $this->repository->resetPassword($request);

        if (!$reset) {
            return $this->ResponseError('Password reset failed', 400);
        } else {
            $email = $request['email'];
            $data  = [
                'subject'    => "Amz Alert- Password Reset Successful",
                'email_body' => view('mails.password_reset.password_reset_success')->render(),
                'to'         => [$email],
            ];

            SendEmail::dispatch($data);
            $user  = $this->repository->getUserByEmail($email);
            $token = $this->repository->login($request);

            if (!$token) {
                return $this->ResponseError('Invalid Credentials', null, 'Invalid Credentials', 401);
            }

            $data = [
                'token' => $token,
                'user'  => $user,
            ];

            return $this->ResponseSuccess(['data' => $data], 'Password reset successful', 200);
        }
    }

}
