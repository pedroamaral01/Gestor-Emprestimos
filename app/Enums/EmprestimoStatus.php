<?php

namespace App\Enums;

class EmprestimoStatus
{
    const ATIVO = 'ativo';
    const QUITADO = 'quitado';
    const INADIMPLENTE = 'inadimplente';
    const ATRASADO = 'atrasado';

    public static function preencherSelect(): array
    {
        return [
            self::ATIVO => 'Ativo',
            self::QUITADO => 'Quitado',
            self::INADIMPLENTE => 'Inadimplente',
            self::ATRASADO => 'Em Atraso'
        ];
    }
}
