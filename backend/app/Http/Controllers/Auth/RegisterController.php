<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identification_number' => 'required|unique:users',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users',
            'phone_number' => 'nullable|string|max:15',
            'location' => 'nullable|string|max:255',
            'type' => 'required|in:patient,professional',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'identification_number' => $request->identification_number,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'location' => $request->location,
            'password' => Hash::make($request->identification_number),
            'type' => $request->type,
            'first_time_login' => true,
        ]);

        return response()->json(['message' => 'Usuario creado exitosamente!', 'user' => $user], 201);
    }
}


