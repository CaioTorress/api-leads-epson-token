<?php

namespace App\Http\Requests;

use App\Rules\CnpjRule;
use App\Rules\CpfRule;
use App\Validations\CnpjValidator;
use App\Validations\CpfValidator;
use Illuminate\Foundation\Http\FormRequest;

class ParticipantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:participants',
            'address' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'access_code' => 'required|string|exists:access_codes,access_code',
            'document_type' => 'required|string|in:CPF,CNPJ',
            'document' => [
                'required',
                'string',
                'unique:participants,document',
                function ($attribute, $value, $fail) {
                    $type = $this->type;
                    $document = preg_replace('/\D/', '', $value); // Remove caracteres não numéricos

                    // Verifica o comprimento do documento antes de qualquer validação
                    if ($type === 'CPF' && strlen($document) !== 11) {
                        $fail("O tipo especificado é CPF, mas o documento fornecido não tem 11 dígitos.");
                        return;
                    }

                    if ($type === 'CNPJ' && strlen($document) !== 14) {
                        $fail("O tipo especificado é CNPJ, mas o documento fornecido não tem 14 dígitos.");
                        return;
                    }

                    // Validação específica de CPF ou CNPJ
                    if ($type === 'CPF' && !CpfValidator::isValid($document)) {
                        $fail("O CPF informado não é válido.");
                    }

                    if ($type === 'CNPJ' && !CnpjValidator::isValid($document)) {
                        $fail("O CNPJ informado não é válido.");
                    }
                },
            ],
        ];
    
    }

    public function messages()
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.unique' => 'Este e-mail já está registrado.',
            'address.required' => 'O endereço é obrigatório.',
            'birth_date.required' => 'A data de nascimento é obrigatória.',
            'access_code.required' => 'O código de acesso é obrigatório.',
            'access_code.exists' => 'O código de acesso informado não existe.',
            'type.required' => 'O tipo de documento (CPF ou CNPJ) é obrigatório.',
            'type.in' => 'O tipo de documento deve ser CPF ou CNPJ.',
            'document.required' => 'O documento é obrigatório.',
            'document.unique' => 'Este documento já está registrado.',
        ];
    }
}
