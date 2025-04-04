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
        Schema::create('parcelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emprestimo_id')->constrained();
            
            $table->integer('numero_parcela');
            $table->decimal('valor_parcela', 10, 2);
            $table->date('data_vencimento');
            $table->date('data_pagamento')->nullable();
            $table->decimal('multa_atraso', 10, 2)->default(0);
            $table->enum('status', ['pendente', 'pago', 'atrasado']);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parcelas');
    }
};
