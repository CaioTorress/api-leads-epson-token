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
    protected $luckyNumberService;

    public function __construct(GenerateLuckyNumberService $luckyNumberService)
    {
        $this->luckyNumberService = $luckyNumberService;
    }

    public function register(RegisterRequest $request)
    {
        // Verifica o código de acesso
        $accessCode = AccessCode::where('email', $request->email)
                                ->where('access_code', $request->access_code)
                                ->first();

        if (!$accessCode) {
            return response()->json(['message' => 'Código de acesso inválido.'], 422);
        }

        // Gera o lucky number
        $luckyNumber = $this->luckyNumberService->generateUniqueCode(7);

        // Cria o participante
        $participant = Participant::create([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'access_code' => $request->access_code,
            'password' => Hash::make($request->password),
            'lucky_number' => $luckyNumber,
        ]);

        return response()->json(['message' => 'Registro realizado com sucesso.', 'lucky_number' => $luckyNumber], 201);
    }
}
