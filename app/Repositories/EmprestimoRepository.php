<?php

namespace App\Repositories;

use App\Models\Emprestimo;
use Illuminate\Support\Facades\Auth;

use App\Enums\EmprestimoStatus;
use App\Enums\PagamentoStatus;

class EmprestimoRepository
{
    public function create(array $data)
    {
        return Emprestimo::create($data)->id;
    }

    public function find(int $id)
    {
        return Emprestimo::find($id);
    }

    public function getUserEmprestimos()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return $user->emprestimos()->latest()->paginate(10);
    }

    public function update(int $id, array $data)
    {
        return Emprestimo::where('id', $id)->update($data);
    }

    public function getEmprestimoByCliente(int $clienteId)
    {
        return Emprestimo::with(['parcelas' => function ($query) {
            $query->where('status', '!=', PagamentoStatus::PAGO)
                ->select([
                    'id',
                    'emprestimo_id',
                    'numero_parcela',
                    'valor_parcela',
                    'data_vencimento',
                    'status',
                ])
                ->orderBy('numero_parcela', 'asc');
        }])
            ->where('cliente_id', $clienteId)
            ->where('status', '!=', EmprestimoStatus::QUITADO)
            ->select([
                'id',
                'valor_emprestado',
                'valor_total',
                'parcelas',
                'taxa_juros_mensal',
                'tipo_juros',
                'data_contratacao',
                'status',
            ])
            ->orderBy('data_contratacao', 'desc')
            ->get();
    }
}
