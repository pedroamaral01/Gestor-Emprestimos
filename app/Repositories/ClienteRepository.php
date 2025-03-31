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
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return $user->clientes()->latest()->paginate(10);
    }

    public function getClientsByUser()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return $user->clientes()->select('id', 'nome')->get();
    }
}
