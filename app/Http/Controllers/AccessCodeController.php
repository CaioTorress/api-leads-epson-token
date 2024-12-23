<?php

namespace App\Http\Controllers;

use App\Models\AccessCode;
use Illuminate\Http\Request;

class AccessCodeController extends Controller
{
        /**
     * Armazena um token na base de dados.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'access_code' => 'required|string|unique:access_codes',
        ]);

        AccessCode::create([
            'access_code' => $request->access_code,
        ]);

        return response()->json(['message' => 'Token armazenado com sucesso.'], 201);
    }
}
