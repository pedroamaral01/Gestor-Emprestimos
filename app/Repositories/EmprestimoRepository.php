<?php

namespace App\Repositories;

use App\Models\Emprestimo;
use Illuminate\Support\Facades\Auth;

class EmprestimoRepository
{
    public function create(array $data)
    {
        return Emprestimo::create($data)->id;
    }

    public function find(int $id)
    {
        return Emprestimo::find($id);
    }

    public function getUserEmprestimos()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return $user->emprestimos()->latest()->paginate(10);
    }
}
