<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CriaClienteRequest;

class ClienteController extends Controller
{
    protected $model;

    public function __construct()
    {
        $this->model = Cliente::class;
        $this->middleware('auth');
    }

    // public function index()
    // {
    //     $clientes = Auth::user()->clientes()->latest()->paginate(10);
    //     return view('clientes.index', compact('clientes'));
    // }

    public function create()
    {
        return view('pages.cadastrarcliente');
    }

    public function store(CriaClienteRequest $request)
    {
        try {

            $nomeCompleto = $request->nome . ' ' . $request->sobrenome;

            $data = [
                'nome' => $nomeCompleto,
                'cpf' => $request->cpf,
                'telefone' => $request->telefone,
                'renda' => $request->renda,
                'profissao' => $request->profissao,
                'user_id' => Auth::id()
            ];

            $cliente = new $this->model();
            $cliente->fill($data);
            $cliente->save();

            return redirect()->route('dashboard')
                ->with('success', 'Cliente cadastrado com sucesso!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Erro ao cadastrar cliente: ' . $e->getMessage());
        }
    }

    // Métodos adicionais seguindo o mesmo padrão
    public function find($id)
    {
        return $this->model::find($id);
    }

    public function findAll()
    {
        return $this->model::all();
    }

    public function update(array $data, $id)
    {
        $cliente = $this->model::find($id);
        if ($cliente) {
            $cliente->fill($data);
            $cliente->save();
            return $cliente;
        }
        return null;
    }
}
