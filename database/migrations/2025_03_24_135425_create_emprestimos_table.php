<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('emprestimos', function (Blueprint $table) {
            $table->id();           
            $table->decimal('valor_principal', 12, 2);
            $table->decimal('valor_total', 12, 2);
            $table->integer('parcelas');
            $table->decimal('taxa_juros_mensal', 5, 2);
            $table->enum('tipo_juros', ['simples', 'composto']);
            
            $table->date('data_contratacao');
            $table->date('data_vencimento_primeira_parcela');
            $table->date('data_quitação')->nullable();
            
            $table->enum('status', ['analise', 'ativo', 'atrasado', 'quitado', 'inadimplente']);
            $table->text('finalidade')->nullable();
            
            $table->foreignId('cliente_id')->constrained('clientes');
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emprestimos');
    }
};
