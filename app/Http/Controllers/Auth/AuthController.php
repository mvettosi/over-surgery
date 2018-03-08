<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterFormRequest;
use App\Mail\RegistrationCompleted;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use JWTAuth;
use Mail;

class AuthController extends Controller {
    public function register(RegisterFormRequest $request) {
        $user = new User;
        $user->email = $request->email;
        $user->name = $request->name;
        $user->surname = $request->surname;

        $username = $this->getUsername($request->name, $request->surname);
        $password = $this->getPassword();
        $user->username = $username;
        $user->password = bcrypt($password);

        Mail::to($request->email)->send(new RegistrationCompleted($username, $password));

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

    protected function getUsername($name, $surname) {
        $username = strtolower($name . "." . $surname);
        $userRows = User::whereRaw("username REGEXP '^{$username}([0-9]*)?$'")->get();
        $countUser = count($userRows) + 1;

        return ($countUser > 1) ? "{$username}{$countUser}" : $username;
    }

    protected function getPassword() {
        return str_random(8);
    }
}
