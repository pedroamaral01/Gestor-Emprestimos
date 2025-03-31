<?php

namespace App\Repositories;

use App\Models\Parcela;
use Illuminate\Support\Facades\Auth;

class ParcelaRepository
{
    public function create(array $data): Parcela
    {
        return Parcela::create($data);
    }

    public function find(int $id): ?Parcela
    {
        return Parcela::find($id);
    }
}
