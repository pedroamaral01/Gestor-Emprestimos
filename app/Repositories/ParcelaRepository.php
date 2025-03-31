<?php

namespace App\Repositories;

use App\Models\Parcela;
use Illuminate\Support\Facades\Auth;

class ParcelaRepository
{
    public function create(array $data)
    {
        return Parcela::create($data);
    }

    public function createMany(array $parcelas): bool
    {
        return Parcela::insert($parcelas);
    }

    public function find(int $id)
    {
        return Parcela::find($id);
    }
}
