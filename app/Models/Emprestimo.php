<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Emprestimo extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Tipos de juros disponíveis
     */
    public const TIPO_JUROS = [
        'simples' => 'Juros Simples',
        'composto' => 'Juros Compostos'
    ];

    /**
     * Status disponíveis
     */
    public const STATUS = [
        'analise' => 'Em Análise',
        'ativo' => 'Ativo',
        'atrasado' => 'Atrasado',
        'quitado' => 'Quitado',
        'inadimplente' => 'Inadimplente'
    ];

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

    /**
     * Relacionamento com o cliente
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com as parcelas
     */
    public function parcelas()
    {
        return $this->hasMany(Parcela::class);
    }

    /**
     * Acessor para status formatado
     */
    public function getStatusFormatadoAttribute()
    {
        return self::STATUS[$this->status] ?? 'Desconhecido';
    }

    /**
     * Acessor para tipo de juros formatado
     */
    public function getTipoJurosFormatadoAttribute()
    {
        return self::TIPO_JUROS[$this->tipo_juros] ?? 'Desconhecido';
    }

    /**
     * Escopo para empréstimos ativos
     */
    public function scopeAtivos($query)
    {
        return $query->where('status', 'ativo');
    }

    /**
     * Escopo para empréstimos quitados
     */
    public function scopeQuitados($query)
    {
        return $query->where('status', 'quitado');
    }

    /**
     * Escopo para empréstimos em análise
     */
    public function scopeEmAnalise($query)
    {
        return $query->where('status', 'analise');
    }

    /**
     * Calcula o valor total do empréstimo com juros
     */
    public function calcularValorTotal()
    {
        if ($this->tipo_juros === 'simples') {
            return $this->valor_principal * (1 + ($this->taxa_juros_mensal / 100 * $this->parcelas));
        }

        // Juros compostos
        return $this->valor_principal * pow(1 + ($this->taxa_juros_mensal / 100), $this->parcelas);
    }
}
