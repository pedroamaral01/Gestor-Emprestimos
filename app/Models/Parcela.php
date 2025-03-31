<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Parcela extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_parcela',
        'valor_parcela',
        'data_vencimento',
        'data_pagamento',
        'multa_atraso',
        'status',
        'emprestimo_id'
    ];

    protected $casts = [
        'data_vencimento' => 'date',
        'data_pagamento' => 'date',
        'valor_parcela' => 'decimal:2',
        'multa_atraso' => 'decimal:2'
    ];

    public function emprestimo()
    {
        return $this->belongsTo(Emprestimo::class);
    }
}
