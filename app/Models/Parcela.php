<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parcela extends Model
{
    use HasFactory;

    /**
     * Os atributos que são mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'numero_parcela',
        'valor_parcela',
        'data_vencimento',
        'data_pagamento',
        'multa_atraso',
        'status',
        'emprestimo_id'
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data_vencimento' => 'date',
        'data_pagamento' => 'date',
        'valor_parcela' => 'decimal:2',
        'multa_atraso' => 'decimal:2'
    ];

    /**
     * Status disponíveis para uma parcela.
     */
    public const STATUS = [
        'pendente' => 'Pendente',
        'pago' => 'Pago',
        'atrasado' => 'Atrasado'
    ];

    /**
     * Relacionamento com o modelo Emprestimo.
     */
    public function emprestimo(): BelongsTo
    {
        return $this->belongsTo(Emprestimo::class);
    }

    /**
     * Verifica se a parcela está paga.
     */
    public function isPago(): bool
    {
        return $this->status === 'pago';
    }

    /**
     * Verifica se a parcela está atrasada.
     */
    public function isAtrasado(): bool
    {
        return $this->status === 'atrasado' || 
              ($this->status === 'pendente' && $this->data_vencimento < now());
    }
}