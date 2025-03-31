<?php

namespace App\Services;

use Carbon\Carbon;
use App\Enums\PagamentoStatus;

class ParcelaService
{
    public function prepararParcelas(
        int $emprestimoId,
        float $valorTotal,
        int $qtdParcelas,
        string $dataPrimeiraParcela
    ) {
        $parcelas = [];
        $valorParcela = round($valorTotal / $qtdParcelas, 2);
        $dataVencimento = Carbon::parse($dataPrimeiraParcela);

        for ($i = 1; $i <= $qtdParcelas; $i++) {
            $parcelas[] = [
                'emprestimo_id' => $emprestimoId,
                'numero_parcela' => $i,
                'valor_parcela' => $valorParcela,
                'data_vencimento' => $dataVencimento->format('Y-m-d'),
                'status' => PagamentoStatus::PENDENTE,
            ];

            $dataVencimento->addMonth();
        }

        return $parcelas;
    }
}
