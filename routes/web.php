<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EmprestimoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('pages.dashboard');
})->middleware(['auth', 'verified'])->name('pages.dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('cliente')->group(function () {
        Route::get('/create', [ClienteController::class, 'create'])->name('cliente.create');
        Route::post('/', [ClienteController::class, 'store'])->name('cliente.store');
    });

    Route::prefix('emprestimo')->group(function () {
        Route::get('/create', [EmprestimoController::class, 'create'])->name('emprestimo.create');
        Route::post('/', [EmprestimoController::class, 'store'])->name('emprestimo.store');
        Route::post('/calcular-risco', [EmprestimoController::class, 'calcularRisco'])->name('emprestimo.calcular-risco');
        Route::get('/pagamento', [EmprestimoController::class, 'edit'])->name('emprestimo.edit');
        Route::put('/{emprestimo}', [EmprestimoController::class, 'update'])->name('emprestimo.update');
        Route::post('/pagamento/lista-emprestimos', [EmprestimoController::class, 'listaEmprestimos'])->name('emprestimo.lista-emprestimos');
    });
});

require __DIR__ . '/auth.php';
