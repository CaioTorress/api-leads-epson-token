<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendEmailRequest;
use App\Models\AccessCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class EmailController extends Controller
{
    public function sendEmails(SendEmailRequest $request)
    {
        $alreadySentEmails = [];
        $newlySentEmails = [];

        foreach ($request->emails as $email) {
            // Verifica se o e-mail já tem um código de acesso
            $existingAccessCode = AccessCode::where('email', $email)->first();

            if ($existingAccessCode) {
                $alreadySentEmails[] = $email;
                continue;
            }

            // Gera um novo código de acesso
            $accessCode = Str::random(6);

            // Armazena o código na tabela
            AccessCode::create([
                'email' => $email,
                'access_code' => $accessCode,
            ]);

            // Envia o e-mail
            Mail::raw("Seu código de acesso é: $accessCode", function ($message) use ($email) {
                $message->to($email)->subject('Código de Acesso');
            });

            $newlySentEmails[] = $email;
        }

        // Retorna a resposta indicando quais e-mails foram enviados
        return response()->json([
            'message' => 'Processo concluído.',
            'emails_sent' => $newlySentEmails,
            'emails_already_sent' => $alreadySentEmails,
        ], 200);
    }
}
