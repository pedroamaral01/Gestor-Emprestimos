<?php

namespace App\Http\Controllers;

use App\Repositories\ClienteRepository;
use App\Http\Requests\CriaClienteRequest;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    public function __construct(
        private ClienteRepository $clienteRepository
    ) {}

    public function create()
    {
        return view('pages.cadastrar-cliente');
    }

    public function store(CriaClienteRequest $request)
    {
        try {
            $data = [
                'nome' => $request->nome . ' ' . $request->sobrenome,
                'cpf' => $request->cpf,
                'telefone' => $request->telefone,
                'renda' => $request->renda,
                'profissao' => $request->profissao,
                'user_id' => Auth::id()
            ];

            $this->clienteRepository->create($data);

            return redirect()->route('dashboard')
                ->with('success', 'Cliente cadastrado com sucesso!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Erro ao cadastrar cliente. Por favor, tente novamente.');
        }
    }
}
