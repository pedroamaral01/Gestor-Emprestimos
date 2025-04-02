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
        'valor_emprestado',
        'valor_total',
        'parcelas',
        'taxa_juros_mensal',
        'tipo_juros',
        'data_contratacao',
        'data_quitação',
        'status',
        'finalidade',
        'cliente_id',
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
