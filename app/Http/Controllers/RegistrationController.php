<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\AccessCode;
use App\Models\Participant;
use App\Services\GenerateLuckyNumberService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    public function register(RegisterRequest $request)
    {
        // Verifica o token
        $accessCode = AccessCode::where('access_code', $request->access_code)->first();

        if (!$accessCode) {
            return response()->json(['message' => 'Token inválido.'], 422);
        }

        // Cria o participante
        $participant = Participant::create([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'access_code' => $request->access_code,
            'password' => Hash::make($request->password),
            'lucky_number' => $this->generateLuckyNumber(7), // Gera lucky number aqui
        ]);

        return response()->json(['message' => 'Registro realizado com sucesso.'], 201);
    }

    /**
     * Gera um lucky number único.
     *
     * @param int $digits
     * @return string
     */
    protected function generateLuckyNumber(int $digits): string
    {
        do {
            $min = (int) str_repeat('1', $digits);
            $max = (int) str_repeat('9', $digits);
            $randomNumber = (string) random_int($min, $max);
        } while (Participant::where('lucky_number', $randomNumber)->exists());

        return $randomNumber;
    }
}
