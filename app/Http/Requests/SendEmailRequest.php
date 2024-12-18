<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendEmailRequest extends FormRequest
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
            'emails' => 'required|array|max:5',
            'emails.*' => 'required|email|distinct',
        ];
    }

    public function messages()
    {
        return [
            'emails.required' => 'A lista de e-mails é obrigatória.',
            'emails.array' => 'Os e-mails devem ser enviados em formato de lista.',
            'emails.max' => 'A lista pode conter no máximo 5 e-mails.',
            'emails.*.email' => 'Cada item da lista deve ser um e-mail válido.',
            'emails.*.distinct' => 'Os e-mails na lista devem ser únicos.',
        ];
    }
}
