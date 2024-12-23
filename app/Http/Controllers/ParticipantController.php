<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParticipantRequest;
use App\Models\AccessCode;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class ParticipantController extends Controller
{
    /**
     * Cadastra um novo participante.
     *
     * @param ParticipantRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ParticipantRequest $request)
    {
        // Valida o código de acesso
        $accessCode = AccessCode::where('access_code', $request->access_code)->first();
    
        if (!$accessCode->canRegister()) {
            return response()->json(['message' => 'Este código de acesso já atingiu o limite de cadastros.'], 422);
        }
    
        // Cria o participante
        $participant = Participant::create($request->validated());
    
        // Incrementa o contador de registros do código de acesso
        $accessCode->increment('registration_count');
    
        // Dados para enviar como multipart/form-data
        $payload = [
            ['name' => 'participant_id', 'contents' => $participant->id],
            ['name' => 'campaign_id', 'contents' => 2],
        ];
    
        try {
            // Envia o ID do participante e campaign_id para a API externa como multipart
            $response = Http::asMultipart()->post('https://api-develop-gerador-epson.atelie.app.br/api/lucky-number', $payload);
    
            // Log para depuração
            Log::info('Enviando dados para a API externa', [
                'url' => 'https://api-develop-gerador-epson.atelie.app.br/api/lucky-number',
                'payload' => $payload,
                'response' => $response->body(),
                'status_code' => $response->status(),
            ]);
    
            // Verifica se a requisição foi bem-sucedida
            if ($response->failed()) {
                return response()->json([
                    'message' => 'Participante cadastrado, mas ocorreu um erro ao sincronizar com a API externa.',
                    'participant' => $participant,
                    'api_response' => $response->body(),
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao enviar dados para a API externa', [
                'exception' => $e->getMessage(),
            ]);
            return response()->json([
                'message' => 'Participante cadastrado, mas ocorreu um erro ao sincronizar com a API externa.',
                'participant' => $participant,
            ], 500);
        }
    
        return response()->json([
            'message' => 'Participante cadastrado com sucesso.',
            'participant' => $participant,
        ], 200);
    }

    /**
     * Busca informações do participante pelo CPF e data de nascimento.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $request->validate([
            'document' => 'required|string',
            'birth_date' => 'required|date',
        ]);

        $participant = Participant::where('document', $request->document)
            ->where('birth_date', $request->birth_date)
            ->first();

        if (!$participant) {
            return response()->json(['message' => 'Participante não encontrado.'], 404);
        }

        return response()->json($participant, 200);
    }
}
