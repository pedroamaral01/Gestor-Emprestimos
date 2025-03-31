<?php

namespace App\Enums;

class PagamentoStatus
{
    const PENDENTE = 'pendente';
    const PAGO = 'pago';
    const ATRASADO = 'atrasado';

    public static function preencherSelect(): array
    {
        return [
            self::PENDENTE => 'Pendente',
            self::PAGO => 'Pago',
            self::ATRASADO => 'Em Atraso'
        ];
    }
}
