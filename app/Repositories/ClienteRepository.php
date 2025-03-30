<?php

namespace App\Repositories;

use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;

class ClienteRepository
{
    public function create(array $data): Cliente
    {
        return Cliente::create($data);
    }

    public function find(int $id): ?Cliente
    {
        return Cliente::find($id);
    }

    public function getUserClientes()
    {
        return Auth::user()->clientes()->latest()->paginate(10);
    }
}
