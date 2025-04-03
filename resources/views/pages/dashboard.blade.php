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
                @foreach($emprestimoStatus as $valor => $rotulo)
                <option value="{{ $valor }}">{{ $rotulo }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-12 mt-2">
              <div class="d-flex justify-content-end">
                <button id="btn-limpar" class="btn btn-outline-secondary">
                  <i class="fas fa-broom"></i> Limpar Filtros
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
                  <!-- Dados do JavaScript -->
                </tbody>
              </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal de Garantia -->
  <div class="modal fade" id="modalGarantia" tabindex="-1" aria-labelledby="modalGarantiaLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalGarantiaLabel">Detalhes da Garantia</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <p><strong>Tipo:</strong> <span id="garantia-tipo"></span></p>
          <p><strong>Descrição:</strong> <span id="garantia-descricao"></span></p>
          <p><strong>Valor Avaliado:</strong> R$ <span id="garantia-valor"></span></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {

      let todosEmprestimos = [];

      function getTodosClientesIds() {
        return $('#cliente option')
          .map(function() {
            return $(this).val();
          })
          .get()
          .filter(id => id !== "");
      }

      carregarEmprestimos(getTodosClientesIds());

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
                            <td colspan="7" class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Carregando...</span>
                                </div>
                            </td>
                        </tr>
                    `);
          },
          success: function(response) {
            if (response.success) {
              todosEmprestimos = response.emprestimos;
              aplicarFiltros();
            } else {
              $('#tabela-emprestimos tbody').html(`
                            <tr>
                                <td colspan="7" class="text-center">Nenhum empréstimo encontrado</td>
                            </tr>
                        `);
            }
          },
          error: function() {
            $('#tabela-emprestimos tbody').html(`
                        <tr>
                            <td colspan="7" class="text-center text-danger">Erro ao carregar empréstimos</td>
                        </tr>
                    `);
          }
        });
      }

      function aplicarFiltros() {
        const dataInicio = $('#data-inicio').val();
        const dataFim = $('#data-fim').val();
        const status = $('#status').val();

        let emprestimosFiltrados = [...todosEmprestimos];

        // Filtro por data
        if (dataInicio) {
          const inicio = new Date(dataInicio);
          emprestimosFiltrados = emprestimosFiltrados.filter(e => {
            return new Date(e.data_contratacao) >= inicio;
          });
        }

        if (dataFim) {
          const fim = new Date(dataFim);
          emprestimosFiltrados = emprestimosFiltrados.filter(e => {
            return new Date(e.data_contratacao) <= fim;
          });
        }

        // Filtro por status
        if (status) {
          emprestimosFiltrados = emprestimosFiltrados.filter(e => e.status === status);
        }

        renderizarEmprestimos(emprestimosFiltrados);
      }

      function renderizarEmprestimos(emprestimos) {
        var tbody = $('#tabela-emprestimos tbody').empty();

        emprestimos.forEach(function(emprestimo) {
          var statusClass = {
            'ativo': 'success',
            'quitado': 'primary',
            'atrasado': 'danger'
          } [emprestimo.status] || 'secondary';

          var parcelasPagas = emprestimo.parcelas.filter(p => p.status.toLowerCase() === 'pago').length;
          var parcelasText = `${parcelasPagas}/${emprestimo.parcelas.length || 0}`;

          var tr = $(`
                    <tr>
                        <td>#${emprestimo.id}</td>
                        <td>${emprestimo.cliente_nome}</td>
                        <td>R$ ${parseFloat(emprestimo.valor_total).toFixed(2).replace('.', ',')}</td>
                        <td>${new Date(emprestimo.data_contratacao + "T00:00:00").toLocaleDateString('pt-BR')}</td>
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
                                ${(emprestimo.garantia_tipo || emprestimo.garantia_descricao || emprestimo.garantia_valor) ? `
                                    <button class="btn btn-sm btn-outline-warning btn-ver-garantia" 
                                        data-tipo="${emprestimo.garantia_tipo || 'Não informado'}"
                                        data-descricao="${emprestimo.garantia_descricao || 'Não informado'}"
                                        data-valor="${emprestimo.garantia_valor || '0.00'}"
                                        title="Ver garantias">
                                        <i class="fas fa-shield-alt"></i>
                                    </button>
                                ` : ''}
                            </div>
                        </td>
                    </tr>
                `);

          tr.find('.btn-ver-garantia').click(function() {
            const tipo = $(this).data('tipo');
            const descricao = $(this).data('descricao');
            const valor = $(this).data('valor');

            $('#garantia-tipo').text(tipo);
            $('#garantia-descricao').text(descricao);
            $('#garantia-valor').text(parseFloat(valor).toFixed(2).replace('.', ','));
            $('#modalGarantia').modal('show');
          });

          var trDetalhes = $(`
                    <tr class="detalhes-parcelas" id="detalhes-${emprestimo.id}" style="display: none;">
                        <td colspan="7">
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

          dataConvertida = new Date(parcela.data_vencimento + "T00:00:00").toLocaleDateString('pt-BR');

          html += `
                    <tr>
                        <td>${parcela.numero_parcela}</td>
                        <td>R$ ${parseFloat(parcela.valor_parcela).toFixed(2).replace('.', ',')}</td>
                        <td>${new Date(parcela.data_vencimento).toISOString().split('T')[0].split('-').reverse().join('/')}</td>
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

      // Eventos dos filtros
      $('#cliente').change(function() {
        var clienteId = $(this).val();
        if (clienteId === '') {
          clienteId = getTodosClientesIds();
        }
        carregarEmprestimos(clienteId);
      });

      // Aplica filtros quando mudar status ou datas
      $('#status, #data-inicio, #data-fim').change(function() {
        aplicarFiltros();
      });

      // Limpar filtros
      $('#btn-limpar').click(function() {
        $('#data-inicio').val('');
        $('#data-fim').val('');
        $('#status').val('');
        $('#cliente').val('');
        carregarEmprestimos(getTodosClientesIds());
      });
    });
  </script>
</x-app-layout>