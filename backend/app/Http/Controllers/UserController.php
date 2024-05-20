<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function getPatients()
    {
        try {
            $patients = User::where('type', 'patient')->get();
            return response()->json($patients);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener los pacientes', 'details' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        $user->update($request->all());

        return response()->json($user);
    }
}
