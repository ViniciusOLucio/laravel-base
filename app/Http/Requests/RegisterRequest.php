<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // Permite a requisição
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
            'email' => 'required|email|unique:users,email|max:255',
            'cpf' => ['required', 'string', 'max:30', 'unique:users,cpf', function ($attribute, $value, $fail) {
                $cpf = preg_replace('/\D/', '', $value); // Limpar CPF de caracteres não numéricos

                // Verificar se o CPF tem 11 dígitos
                if (strlen($cpf) !== 11) {
                    $fail("O CPF deve conter 11 dígitos.");
                    return;
                }

                // Verificar se o CPF é um CPF comum inválido
                $invalidCpfs = [
                    '00000000000',
                    '11111111111',
                    '22222222222',
                    '33333333333',
                    '44444444444',
                    '55555555555',
                    '66666666666',
                    '77777777777',
                    '88888888888',
                    '99999999999',
                ];
                if (in_array($cpf, $invalidCpfs)) {
                    $fail("O CPF fornecido é inválido.");
                    return;
                }

                // Validar os dígitos verificadores do CPF
                for ($t = 9; $t < 11; $t++) {
                    $d = 0;
                    for ($c = 0; $c < $t; $c++) {
                        $d += $cpf[$c] * (($t + 1) - $c);
                    }
                    $d = ((10 * $d) % 11) % 10;
                    if ($cpf[$c] != $d) {
                        $fail("O CPF fornecido é inválido.");
                        return;
                    }
                }
            }],


            'phone' => ['required', 'string', function ($attribute, $value, $fail) {
                // Remover todos os caracteres não numéricos
                $phone = preg_replace('/\D/', '', $value);

                // Verificar se o telefone tem 11 dígitos
                if (strlen($phone) !== 11) {
                    $fail('O número de telefone deve ter 11 dígitos.');
                    return;
                }

                // Validar o DDD (os 2 primeiros dígitos)
                $validDdDs = [
                    '11', '12', '13', '14', '15', '16', '17', '18', '19', // São Paulo
                    '21', '22', '24', // Rio de Janeiro
                    '27', '28', // Espírito Santo
                    '31', '32', '33', '34', '35', '37', '38', // Minas Gerais
                    '41', '42', '43', '44', '45', '46', // Paraná
                    '47', '48', '49', // Santa Catarina
                    '51', '53', '54', '55', // Rio Grande do Sul
                    '61', '62', '63', '64', '65', '66', '67', '68', '69', // Centro-Oeste
                    '71', '73', '74', '75', '77', '79', // Bahia e Sergipe
                    '81', '82', '83', '84', '85', '86', '87', '88', '89', // Nordeste
                    '91', '92', '93', '94', '95', '96', '97', '98', '99', // Norte
                ];

                // Extrai o DDD do número
                $ddd = substr($phone, 0, 2);
                if (!in_array($ddd, $validDdDs)) {
                    $fail('O DDD informado é inválido.');
                }
            }],
            'password' => 'required|string|confirmed|min:8',
        ];
    }
}
