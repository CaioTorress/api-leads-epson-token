<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $participant = Participant::where('email', $request->email)->first();

        if (!$participant || !Hash::check($request->password, $participant->password)) {
            return response()->json(['message' => 'Credenciais inválidas.'], 401);
        }

        $token = $participant->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login realizado com sucesso.',
            'token' => $token,
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout realizado com sucesso.'], 200);
    }
}
