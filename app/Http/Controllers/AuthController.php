<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:users',
            'password' => 'required'
        ]);

        $user  = new User();
        $user->name = $request->name;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'registrado correctamente']);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'name' => ['required'],
            'password' => ['required']
        ]);

        if (Auth::attempt($credentials))
        {
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;
            $cookie = cookie('cookie_token', $token, 60 * 24);
            return response(['token' => $token], Response::HTTP_OK)->withoutCookie($cookie);
        } else {
            return response(['message' => 'Credenciales invalidas'], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function logout()
    {
        $cookie = Cookie::forget('cookie_token');
        return response(['message' => 'Cierre de session correcto'])->withCookie($cookie);
    }
}