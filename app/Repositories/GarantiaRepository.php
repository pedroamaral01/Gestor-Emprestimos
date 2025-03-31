<?php

namespace App\Repositories;

use App\Models\Garantia;
use Illuminate\Support\Facades\Auth;

class GarantiaRepository
{
    public function create(array $data)
    {
        return Garantia::create($data);
    }

    public function find(int $id)
    {
        return Garantia::find($id);
    }
}
