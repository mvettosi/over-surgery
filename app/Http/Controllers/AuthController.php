<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterFormRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use JWTAuth;

class AuthController extends Controller {
    public function register(RegisterFormRequest $request) {
        $user = new User;
        $user->email = $request->email;
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        $user->save();
        return response([
            'status' => 'success',
            'data' => $user,
        ], 200);
    }

    public function login(Request $request) {
        $credentials = $request->only('email', 'password');
        if (!$token = JWTAuth::attempt($credentials)) {
            return response([
                'status' => 'error',
                'error' => 'invalid.credentials',
                'msg' => 'Invalid Credentials.',
            ], 400);
        }
        return response([
            'status' => 'success',
        ])
            ->header('Authorization', $token);
    }

    public function user(Request $request) {
        $user = User::find(Auth::user()->id);
        return response([
            'status' => 'success',
            'data' => $user,
        ]);
    }
    public function refresh() {
        return response([
            'status' => 'success',
        ]);
    }

    public function logout() {
        JWTAuth::invalidate();
        return response([
            'status' => 'success',
            'msg' => 'Logged out Successfully.',
        ], 200);
    }

    /**
     * API Recover Password
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function recover(Request $request) {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $error_message = "Your email address was not found.";
            return response()->json(['success' => false, 'error' => ['email' => $error_message]], 401);
        }
        try {
            Password::sendResetLink($request->only('email'), function (Message $message) {
                $message->subject('Your Password Reset Link');
            });
        } catch (\Exception $e) {
            //Return with error
            $error_message = $e->getMessage();
            return response()->json(['success' => false, 'error' => $error_message], 401);
        }
        return response()->json([
            'success' => true, 'data' => ['message' => 'A reset email has been sent! Please check your email.'],
        ]);
    }
}
