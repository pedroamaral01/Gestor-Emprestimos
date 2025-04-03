<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    $parcelasAfetadas = DB::table('parcelas')
        ->where('data_vencimento', '<', now())
        ->whereNull('data_pagamento')
        ->update(['status' => 'atrasado']);

    $emprestimosAtrasados = DB::table('parcelas')
        ->where('status', 'atrasado')
        ->distinct()
        ->pluck('emprestimo_id');

    if ($emprestimosAtrasados->isNotEmpty()) {
        DB::table('emprestimos')
            ->whereIn('id', $emprestimosAtrasados)
            ->update(['status' => 'atrasado']);

        Log::info(count($emprestimosAtrasados) . ' empréstimos foram marcados como "atrasado".');
    }

    if ($parcelasAfetadas > 0) {
        Log::info($parcelasAfetadas . ' parcelas marcadas como "atrasado".');
    }
})->day();
