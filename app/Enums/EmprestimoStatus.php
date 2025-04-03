<?php

namespace App\Enums;

class EmprestimoStatus
{
    const ATIVO = 'ativo';
    const QUITADO = 'quitado';
    const ATRASADO = 'atrasado';

    public static function preencherSelect(): array
    {
        return [
            self::ATIVO => 'Ativo',
            self::QUITADO => 'Quitado',
            self::ATRASADO => 'Em Atraso'
        ];
    }
}
