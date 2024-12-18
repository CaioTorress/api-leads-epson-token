<?php

namespace App\Traits;

trait ApiResponse
{
    private static $STATUS = [
        'Success' => 200,
        'Created' => 201,
        'NoContent' => 204,
        'Unauthorized' => 401, // This code causes log out in the user
        'Forbidden' => 403,
        'NotAcceptable' => 406,
        'Unprocessable' => 422,
        'ServerError' => 500,
    ];

    private function response($data, string $message,  string $title, int $status)
    {
        if(request()->header('Export'))
            return $data;

        return response()->json([
            'title' => $title,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    public function success($data, string $message = '',  string $title = "Sucesso!")
    {
        return $this->response(
            $data, 
            $message,
            $title,
            self::$STATUS['Success']
        );
    }

    public function created($data, string $message = '',  string $title = "Criado com sucesso!")
    {
        return $this->response(
            $data, 
            $message,
            $title,
            self::$STATUS['Created']
        );
    }

    public function forbidden( string $message = '', string $title = "Impossível processar")
    {
        return $this->response(
            [], 
            $message,
            $title,
            self::$STATUS['Forbidden']
        );
    }

    public function notAcceptable(string $message = '', string $title = "Envio incorreto")
    {
        return $this->response(
            [], 
            $message,
            $title,
            self::$STATUS['NotAcceptable']
        );
    }

    public function deleted($data, string $message = '', string $title = "Removido com sucesso")
    {
        return $this->response(
            $data, 
            $message,
            $title,
            self::$STATUS['NoContent']
        );
    }

    public function updated($data, string $message = '', string $title = "Editado com sucesso")
    {
        return $this->response(
            $data, 
            $message,
            $title,
            self::$STATUS['NoContent']
        );
    }

    public function unauthorized(string $message = 'Por favor faça o login novamente', string $title = "Cliente Deslogado")
    {
        return $this->response(
            [], 
            $message,
            $title,
            self::$STATUS['Unauthorized']
        );
    }

    public function serverError($data, string $message = '', $title = "Erro no Servidor")
    {
        return $this->response(
            $data, 
            $message, 
            $title,
            self::$STATUS['ServerError']
        );
    }

    public function Unprocessable(string $message = "Impossível processar", string $title = 'Falha')
    {
        return $this->response(
            [], 
            $message, 
            $title,
            self::$STATUS['Unprocessable']
        );
    }
}