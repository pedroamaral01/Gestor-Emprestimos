<?php

namespace App\Services;

class CalculaValorTotalService
{
    public function calculaValorTotal(float $valor, int $qtdParcelas, float $percentualJuros, string $tipoJuros)
    {
        $valorTotal = $valor;
        $juros = 0;

        if ($tipoJuros === 'simples') {
            $juros = $valor * ($percentualJuros / 100) * $qtdParcelas;
        } else {
            $juros = $valor * ((1 + ($percentualJuros / 100)) ** $qtdParcelas) - $valor;
        }

        $valorTotal += $juros;

        return $valorTotal;
    }
}
