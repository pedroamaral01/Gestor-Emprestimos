<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Emprestimo extends Model
{
    use HasFactory, SoftDeletes;
    /**
     * Atributos que podem ser preenchidos em massa
     */
    protected $fillable = [
        'user_id',
        'valor_principal',
        'valor_total',
        'parcelas',
        'taxa_juros_mensal',
        'tipo_juros',
        'data_contratacao',
        'data_vencimento_primeira_parcela',
        'data_quitação',
        'status',
        'finalidade',
        'cliente_id',
    ];

    /**
     * Conversões de tipo
     */
    protected $casts = [
        'valor_principal' => 'decimal:2',
        'valor_total' => 'decimal:2',
        'taxa_juros_mensal' => 'decimal:2',
        'data_contratacao' => 'date',
        'data_vencimento_primeira_parcela' => 'date',
        'data_quitação' => 'date'
    ];

    public function cliente()
    {
        return $this->belongsTo(User::class, 'cliente_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function parcelas()
    {
        return $this->hasMany(Parcela::class);
    }

    public function garantia()
    {
        return $this->hasMany(Garantia::class);
    }
}
