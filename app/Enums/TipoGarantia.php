<?php

namespace App\Enums;

class TipoGarantia
{
    const VEICULO = 'veiculo';
    const IMOVEL = 'imovel';
    const FIADOR = 'fiador';
    const OUTROS = 'outros';

    public static function preencherSelect(): array
    {
        return [
            self::VEICULO => 'Veículo',
            self::IMOVEL => 'Imóvel',
            self::FIADOR => 'Fiador',
            self::OUTROS => 'Outros'
        ];
    }
}
