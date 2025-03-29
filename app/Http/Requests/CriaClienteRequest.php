<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CriaClienteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:255',
            'sobrenome' => 'required|string|max:255',
            'telefone' => 'required|string|max:20',
            'renda' => 'required|numeric|min:0',
            'cpf' => 'required|string|max:14|unique:clientes,cpf',
            'profissao' => 'nullable|string|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O campo nome é obrigatório',
            'sobrenome.required' => 'O campo sobrenome é obrigatório',
            'telefone.required' => 'O campo telefone é obrigatório',
            'renda.required' => 'O campo renda é obrigatório',
            'cpf.required' => 'O campo cpf é obrigatório',
            'profissao.required' => 'O campo profissão é obrigatório',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'cpf' => preg_replace('/[^0-9]/', '', $this->cpf),
        ]);
    }
}
