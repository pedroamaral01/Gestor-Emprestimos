<x-app-layout>
  <div class="container-fluid">
    <div class="content flex-grow-1 p-4">
      @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
      @endif

      <div class="card shadow">
        <div class="card-header bg-primary text-white">
          <h3 class="card-title mb-0">Dashboard de Empréstimos</h3>
        </div>

        <div class="card-body">
          <!-- Filtros -->
          <div class="row mb-4 g-3">
            <div class="col-md-3">
              <label for="data-inicio" class="form-label">Data Início</label>
              <input type="date" class="form-control" id="data-inicio">
            </div>
            <div class="col-md-3">
              <label for="data-fim" class="form-label">Data Fim</label>
              <input type="date" class="form-control" id="data-fim">
            </div>
            <div class="col-md-3">
              <label for="cliente" class="form-label">Cliente</label>
              <select class="form-select" id="cliente">
                <option value="">Todos os Clientes</option>
                @foreach($clientes as $cliente)
                <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3">
              <label for="status" class="form-label">Status</label>
              <select class="form-select" id="status">
                <option value="">Todos</option>
                <option value="ativo">Ativo</option>
                <option value="quitado">Quitado</option>
                <option value="atrasado">Atrasado</option>
              </select>
            </div>
            <div class="col-md-12 mt-2">
              <div class="d-flex justify-content-end">
                <button id="btn-filtrar" class="btn btn-primary me-2">
                  <i class="fas fa-filter"></i> Filtrar
                </button>
                <button id="btn-limpar" class="btn btn-outline-secondary">
                  <i class="fas fa-broom"></i> Limpar
                </button>
              </div>
            </div>
          </div>

          <!-- Tabela de Empréstimos -->
          <div class="table-responsive">
            <table class="table table-hover" id="tabela-emprestimos">
              <table id="tabela-emprestimos" class="table table-striped">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Valor Total</th>
                    <th>Data Contratação</th>
                    <th>Status</th>
                    <th>Parcelas</th>
                    <th>Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Os dados serão inseridos aqui via JavaScript -->
                </tbody>
              </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      // Carregar dados iniciais (todos os empréstimos)
      carregarEmprestimos();

      function carregarEmprestimos(clienteId = null) {
        $.ajax({
          url: "{{ route('emprestimo.lista-emprestimos') }}",
          type: "POST",
          data: {
            _token: "{{ csrf_token() }}",
            cliente_id: clienteId
          },
          beforeSend: function() {
            $('#tabela-emprestimos tbody').html(`
                    <tr>
                        <td colspan="6" class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Carregando...</span>
                            </div>
                        </td>
                    </tr>
                `);
          },
          success: function(response) {
            if (response.success) {
              renderizarEmprestimos(response.emprestimos);
            } else {
              $('#tabela-emprestimos tbody').html(`
                        <tr>
                            <td colspan="6" class="text-center">Nenhum empréstimo encontrado</td>
                        </tr>
                    `);
            }
          },
          error: function() {
            $('#tabela-emprestimos tbody').html(`
                    <tr>
                        <td colspan="6" class="text-center text-danger">Erro ao carregar empréstimos</td>
                    </tr>
                `);
          }
        });
      }

      function renderizarEmprestimos(emprestimos) {
        console.log(emprestimos);
        var tbody = $('#tabela-emprestimos tbody').empty();

        emprestimos.forEach(function(emprestimo) {
          var statusClass = {
            'ativo': 'success',
            'quitado': 'primary',
            'atrasado': 'danger'
          } [emprestimo.status] || 'secondary';

          var dataFormatada = new Date(emprestimo.data_contratacao).toLocaleDateString('pt-BR');
          var parcelasPagas = emprestimo.parcelas.filter(p => p.status.toLowerCase() === 'pago').length;
          var parcelasText = `${parcelasPagas}/${emprestimo.parcelas.length || 0}`;

          // Linha principal
          var tr = $(`
    <tr>
        <td>#${emprestimo.id}</td>
        <td>${emprestimo.cliente_nome}</td>
        <td>R$ ${parseFloat(emprestimo.valor_total).toFixed(2).replace('.', ',')}</td>
        <td>${dataFormatada}</td>
        <td>
            <span class="badge bg-${statusClass}">
                ${emprestimo.status.toUpperCase()}
            </span>
        </td>
        <td>${parcelasText}</td>
        <td>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-primary btn-ver-parcelas" data-id="${emprestimo.id}" title="Ver parcelas">
                    <i class="fas fa-list"></i>
                </button>
                <button class="btn btn-sm btn-outline-warning btn-ver-garantia" data-emprestimo-id="${emprestimo.id}" title="Ver garantias">
                    <i class="fas fa-shield-alt"></i>
                </button>
            </div>
        </td>
    </tr>
`);

          tr.find('.btn-ver-garantia').click(function() {
            var emprestimoId = $(this).data('emprestimo-id');
            abrirModalGarantia(emprestimoId);
          });

          // Linha de detalhes (parcelas)
          var trDetalhes = $(`
                <tr class="detalhes-parcelas" id="detalhes-${emprestimo.id}" style="display: none;">
                    <td colspan="6">
                        <div class="p-3">
                            <h6>Parcelas do Empréstimo #${emprestimo.id}</h6>
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Parcela</th>
                                        <th>Valor</th>
                                        <th>Vencimento</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${renderizarParcelas(emprestimo.parcelas)}
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
            `);

          tbody.append(tr);
          tbody.append(trDetalhes);
        });

        // Adiciona evento de clique para mostrar/ocultar parcelas
        $('.btn-ver-parcelas').click(function() {
          var id = $(this).data('id');
          $('#detalhes-' + id).toggle();
        });
      }

      function renderizarParcelas(parcelas) {
        var html = '';
        parcelas.forEach(function(parcela) {
          var statusClass = {
            'pago': 'success',
            'pendente': 'warning',
            'atrasado': 'danger'
          } [parcela.status] || 'secondary';

          var dataVencimento = new Date(parcela.data_vencimento).toLocaleDateString('pt-BR');

          html += `
                <tr>
                    <td>${parcela.numero_parcela}</td>
                    <td>R$ ${parseFloat(parcela.valor_parcela).toFixed(2).replace('.', ',')}</td>
                    <td>${dataVencimento}</td>
                    <td>
                        <span class="badge bg-${statusClass}">
                            ${parcela.status.toUpperCase()}
                        </span>
                    </td>
                </tr>
            `;
        });
        return html;
      }

      // Filtrar por cliente
      $('#cliente').change(function() {
        var clienteId = $(this).val();
        carregarEmprestimos(clienteId);
      });

      // Limpar filtros
      $('#btn-limpar').click(function() {
        $('#cliente').val('');
        carregarEmprestimos(); // Carrega todos sem filtro
      });
    });
  </script>
</x-app-layout>