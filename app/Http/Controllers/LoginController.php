<?php

namespace App\Http\Controllers;

use Auth;
use JWTAuth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    /**
     * Login user and return token
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request) {
        $request->validate([
            'email' =>'required|email|string',
            'password' => 'required|min:8'
        ]);
        $token = $this->guard($request->email, $request->password);

        if($token) {
            return response()->json(['token' => $token], Response::HTTP_OK)->header('Authorization', $token);
        } else {
            return response()->json(null, Response::HTTP_BAD_REQUEST)->send();
        }
    }

    public function guard($email, $password) {
        return JWTAuth::attempt([
            'email' => $email,
            'password' => $password
        ]);
    }
}
