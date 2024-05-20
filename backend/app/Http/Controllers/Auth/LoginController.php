<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'identification_number' => 'required',
            'password' => 'required',
            'user_type' => 'required|in:patient,professional',
        ]);

        $credentials = $request->only('identification_number', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Credenciales incorrectas'], 401);
        }

        $user = Auth::user();

        if ($user->type !== $request->user_type) {
            return response()->json(['error' => 'Tipo de usuario incorrecto'], 401);
        }

        if ($user->first_time_login) {
            return response()->json(['message' => 'Primera vez, necesita cambiar la contraseña', 'first_time' => true, 'user' => $user, 'token' => $token], 200);
        }

        return response()->json(['token' => $token, 'first_time' => false, 'user_type' => $user->type], 200);
    }

    public function updatePassword(Request $request)
    {
        try {
            $request->validate([
                'new_password' => 'required|string|min:8|confirmed',
            ]);

            $user = Auth::user();

            if (!$user) {
                return response()->json(['error' => 'Usuario no autenticado'], 401);
            }

            $user->password = Hash::make($request->new_password);
            $user->first_time_login = false;
            $user->save();

            return response()->json(['message' => 'Contraseña actualizada correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Se produjo un error al actualizar la contraseña', 'exception' => $e->getMessage()], 500);
        }
    }
}
