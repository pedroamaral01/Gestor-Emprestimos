<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PagamentoRequest extends FormRequest
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
            'parcela_id' => 'required|exists:parcelas,id',
            'emprestimo_id' => 'required|exists:emprestimos,id',
            'multa_atraso' => 'nullable|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'parcela_id.required' => 'Selecione uma parcela',
            'parcela_id.exists' => 'Parcela inválida',
            'emprestimo_id.required' => 'Selecione um empréstimo',
            'emprestimo_id.exists' => 'Empréstimo inválido',
            'multa_atraso.numeric' => 'Multa deve ser um valor numérico',
        ];
    }
}
