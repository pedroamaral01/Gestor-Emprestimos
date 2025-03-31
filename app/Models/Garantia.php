<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Garantia extends Model
{
    use HasFactory;

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
        return $this->belongsTo(Emprestimo::class, 'emprestimo_id', 'id');
    }
}
