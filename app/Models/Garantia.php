<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Garantia extends Model
{
    use HasFactory;

    /**
     * Os tipos de garantia disponíveis.
     */
    public const TIPOS = [
        'veiculo' => 'Veículo',
        'imovel' => 'Imóvel',
        'fiador' => 'Fiador',
        'outros' => 'Outros'
    ];

    protected $fillable = [
        'emprestimo_id',
        'tipo',
        'descricao',
        'valor_avaliado'
    ];

    protected $casts = [
        'valor_avaliado' => 'decimal:2',
    ];

    public function emprestimo()
    {
        return $this->belongsTo(Emprestimo::class);
    }

    public function getTipoFormatadoAttribute()
    {
        return self::TIPOS[$this->tipo] ?? 'Desconhecido';
    }

