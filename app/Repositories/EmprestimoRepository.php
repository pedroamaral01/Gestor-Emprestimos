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

    public function getEmprestimoByClientes(
        array $clientesIds,
        ?bool $somenteNaoQuitados = null
    ) {
        return Emprestimo::with(['parcelas' => function ($query) use ($somenteNaoQuitados) {
            if ($somenteNaoQuitados) {
                $query->where('status', '!=', PagamentoStatus::PAGO);
            }
            $query->select([
                'id',
                'emprestimo_id',
                'numero_parcela',
                'valor_parcela',
                'data_vencimento',
                'status',
            ])
                ->orderBy('numero_parcela', 'asc');
        }])
            ->join('clientes', 'emprestimos.cliente_id', '=', 'clientes.id')
            ->leftJoin('garantias', 'emprestimos.id', '=', 'garantias.emprestimo_id')
            ->whereIn('emprestimos.cliente_id', $clientesIds)
            ->when($somenteNaoQuitados, function ($query) {
                $query->where('emprestimos.status', '!=', EmprestimoStatus::QUITADO);
            })
            ->select([
                'emprestimos.id',
                'emprestimos.cliente_id',
                'clientes.nome as cliente_nome',
                'emprestimos.valor_emprestado',
                'emprestimos.valor_total',
                'emprestimos.parcelas',
                'emprestimos.taxa_juros_mensal',
                'emprestimos.tipo_juros',
                'emprestimos.data_contratacao',
                'emprestimos.status',
                'garantias.descricao as garantia_descricao',
                'garantias.valor_avaliado as garantia_valor',
                'garantias.tipo as garantia_tipo',
            ])
            ->orderBy('emprestimos.id', 'desc')
            ->get();
    }
}
