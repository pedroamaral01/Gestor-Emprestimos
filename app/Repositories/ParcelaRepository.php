<?php

namespace App\Repositories;

use App\Models\Parcela;
use App\Enums\PagamentoStatus;
use Illuminate\Support\Facades\Auth;

class ParcelaRepository
{
    public function create(array $data)
    {
        return Parcela::create($data);
    }

    public function createMany(array $parcelas): bool
    {
        return Parcela::insert($parcelas);
    }

    public function find(int $id)
    {
        return Parcela::find($id);
    }

    public function update(int $idParcela, int $idEmprestimo, array $data)
    {
        $updated = Parcela::where('id', $idParcela)->update($data);

        if (!$updated) {
            return false;
        }

        $parcelasPendentes = Parcela::where('emprestimo_id', $idEmprestimo)
            ->where('status', '!=', PagamentoStatus::PAGO)
            ->count();

        return $parcelasPendentes == 0;
    }
}
