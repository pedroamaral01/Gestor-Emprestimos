<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CalculaRiscoRequest extends FormRequest
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
        $rules = [
            'cliente_id' => 'required|exists:clientes,id',
            'valor' => 'required|numeric|min:0.01',
            'qtd_parcelas' => 'required|integer|min:1',
            'tipo_juros' => 'required|in:simples,composto',
            'percentual_juros' => 'required|numeric|min:0',
            'data_contratacao' => 'required|date',
            'data_vencimento_primeira_parcela' => 'required|date|after_or_equal:data_contratacao',

            'garantia_tipo' => 'nullable|string',
            'garantia_valor_avaliado' => 'nullable|numeric|min:0',
        ];

        $anyGarantiaFieldFilled = $this->garantia_tipo || $this->garantia_valor_avaliado;

        if ($anyGarantiaFieldFilled) {
            $rules['garantia_tipo'] = 'required|string';
            $rules['garantia_valor_avaliado'] = 'required|numeric|min:0';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'cliente_id.required' => 'Selecione um cliente',
            'cliente_id.exists' => 'Cliente inválido',

            'valor.required' => 'O valor do empréstimo é obrigatório',
            'valor.min' => 'O valor mínimo deve ser R$ 0,01',

            'qtd_parcelas.min' => 'O número mínimo de parcelas é 1',

            'tipo_juros.in' => 'Tipo de juros inválido',
            'tipo_juros.required' => 'Selecione o tipo de juros',

            'percentual_juros.required' => 'Informe o percentual de juros',
            'percentual_juros.min' => 'O percentual de juros deve ser maior ou igual a 0',
            'percentual_juros.numeric' => 'O percentual de juros deve ser um valor numérico',

            'data_contratacao.required' => 'Informe a data de contratação',
            'data_contratacao.date' => 'Data de contratação inválida',

            'data_vencimento_primeira_parcela.required' => 'Informe a data de vencimento da primeira parcela',
            'data_vencimento_primeira_parcela.date' => 'Data de vencimento inválida',
            'data_vencimento_primeira_parcela.after_or_equal' => 'A data de vencimento deve ser igual ou posterior à data de contratação',

            'garantia_tipo.required' => 'O tipo de garantia é obrigatório quando informar qualquer dado de garantia',
            'garantia_valor_avaliado.required' => 'O valor avaliado é obrigatório quando informar qualquer dado de garantia',
        ];
    }
}
