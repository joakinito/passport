<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);


        if (Auth::check()) {
            return response()->json(['message' => '¡Ya estás logeado!'], 200);
        }


        if (filter_var($request->username, FILTER_VALIDATE_EMAIL)) {
            $fieldType = 'email';
        } else {
            $fieldType = 'name';
        }

        if (!Auth::attempt([$fieldType => $request->username, 'password' => $request->password])) {
            throw ValidationException::withMessages([
                'username' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }


        $user = Auth::user();


        $token = $user->createToken('authToken')->accessToken;

        return response()->json(['message' => '¡Te has logueado correctamente!', 'access_token' => $token], 200);
    }
}
