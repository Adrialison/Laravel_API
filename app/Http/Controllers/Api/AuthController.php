<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|string|email|unique:users,correo',
            'contraseña' => 'required|string|min:6',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:15',
            'rol' => 'in:admin,cliente'
        ]);

        $user = User::create([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'contraseña' => bcrypt($request->contraseña),
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'rol' => $request->rol ?? 'cliente',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'usuario' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|string|email',
            'contraseña' => 'required|string',
        ]);

        $user = User::where('correo', $request->correo)->first();

        if (!$user || !Hash::check($request->contraseña, $user->contraseña)) {
            throw ValidationException::withMessages([
                'correo' => ['Credenciales incorrectas.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'usuario' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['mensaje' => 'Sesión cerrada correctamente']);
    }
}
