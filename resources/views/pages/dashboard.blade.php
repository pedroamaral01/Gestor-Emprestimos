<div class="container-fluid p-4">
  <div class="row">
    <!-- Cartões de Resumo -->
    <div class="col-md-3">
      <div class="card bg-success text-white">
        <div class="card-body text-center">
          <h5>Valor Recebido</h5>
          <h3>R$ {{ number_format($valorRecebido, 2, ',', '.') }}</h3>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-danger text-white">
        <div class="card-body text-center">
          <h5>Valor Emprestado</h5>
          <h3>R$ {{ number_format($valorEmprestado, 2, ',', '.') }}</h3>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-warning text-dark">
        <div class="card-body text-center">
          <h5>Nº Empréstimos Ativos</h5>
          <h3>{{ $emprestimosAtivos }}</h3>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card bg-info text-white">
        <div class="card-body text-center">
          <h5>Nº Empréstimos Quitados</h5>
          <h3>{{ $emprestimosQuitados }}</h3>
        </div>
      </div>
    </div>
  </div>

  <!-- Barra de Pesquisa -->
  <div class="row mt-4">
    <div class="col-md-12">
      <form method="GET" action="" class="d-flex align-items-center gap-2">
        @csrf
        <input type="text" class="form-control" placeholder="Pesquisar" name="pesquisa" value="{{ request('pesquisa') }}">
        <button type="submit" class="btn btn-secondary">
          <i class="bi bi-search"></i>
        </button>
        <button type="button" class="btn btn-secondary">Filtros</button>
      </form>
    </div>
  </div>

  <!-- Tabela de Empréstimos -->
  <div class="table-responsive mt-4">
    <table class="table table-bordered table-hover">
      <thead class="table-light">
        <tr>
          <th>Ações</th>
          <th>CPF/CNPJ</th>
          <th>Nome</th>
          <th>Telefone</th>
          <th>Data Início</th>
          <th>Parcelas Restantes</th>
          <th>Valor da Parcela</th>
          <th>Valor total dívida</th>
          <th>Dia de Pagamento</th>
          <th>Situação</th>
        </tr>
      </thead>
      <tbody>
        @foreach($emprestimos as $e)
        <tr>
          <td>
            <a href="{{ route('emprestimos.show', $e->id) }}" class="btn btn-success btn-sm me-1">
              <i class="bi bi-eye"></i>
            </a>
            <a href="{{ route('emprestimos.edit', $e->id) }}" class="btn btn-warning btn-sm me-1">
              <i class="bi bi-pencil"></i>
            </a>
            <form action="{{ route('emprestimos.destroy', $e->id) }}" method="POST" class="d-inline">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir?')">
                <i class="bi bi-trash"></i>
              </button>
            </form>
          </td>
          <td>{{ $e->cpf }}</td>
          <td>{{ $e->nome }}</td>
          <td>{{ $e->telefone }}</td>
          <td>{{ $e->data_inicio->format('d/m/Y') }}</td>
          <td>{{ $e->parcelas_restantes }}</td>
          <td>R$ {{ number_format($e->valor_parcela, 2, ',', '.') }}</td>
          <td>R$ {{ number_format($e->valor_total, 2, ',', '.') }}</td>
          <td>{{ $e->dia_pagamento }}</td>
          <td>
            <span class="{{ 
              str_contains($e->situacao, 'atraso') ? 'text-danger' : 
              ($e->situacao === 'Quitado' ? 'text-success' : 'text-warning') 
            }}">
              {{ $e->situacao }}
            </span>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>