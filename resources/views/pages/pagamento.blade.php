<x-app-layout>
    <div class="container-fluid">
        <div class="content flex-grow-1 p-4">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="shadow">
                <div>
                    <h3 class="text-center bg-gray-100 p-4">Pagamento</h3>
                </div>
                <div class="p-4">
                    <!-- Cliente -->
                    <div class="col-md-6 mb-3">
                        <label for="cliente_id" class="form-label">Cliente *</label>
                        <select class="form-select {{ $errors->has('cliente_id') ? 'is-invalid' : '' }}"
                            id="cliente_id"
                            name="cliente_id">
                            <option value="" selected disabled>Selecione um cliente</option>
                            @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                {{ $cliente->nome }}
                            </option>
                            @endforeach
                        </select>
                        @error('cliente_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Container para os empréstimos -->
                    <div id="emprestimos-container" class="mb-4" style="display: none;">
                        <h5>Selecione um Empréstimo</h5>
                        <div id="lista-emprestimos" class="list-group mb-3"></div>
                    </div>

                    <!-- Container para as parcelas -->
                    <div id="parcelas-container" style="display: none;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de pagamento (agora único) -->
    <div class="modal fade" id="modalPagar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Pagamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formPagar" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="parcela_id" id="parcela_id">
                    <input type="hidden" name="emprestimo_id" id="emprestimo_id">
                    <div class="modal-body">
                        <div class="mb-3" id="multaContainer" style="display: none;">
                            <label for="multa_atraso" class="form-label">Multa por Atraso (R$)</label>
                            <input type="number" step="0.01" class="form-control" name="multa_atraso" id="multa_atraso" min="0">
                        </div>
                        <p>Tem certeza que deseja registrar o pagamento desta parcela?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Confirmar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var responseData = null;

            $('#cliente_id').change(function() {
                var clienteId = $(this).val();
                if (clienteId) {
                    loadEmprestimos(clienteId);
                } else {
                    $('#lista-emprestimos').empty();
                    $('#emprestimos-container').hide();
                    $('#parcelas-container').hide().empty();
                }
            });

            function loadEmprestimos(clienteId) {
                $.ajax({
                    url: "{{ route('emprestimo.lista-emprestimos') }}",
                    type: "POST",
                    data: {
                        cliente_id: clienteId,
                        _token: "{{ csrf_token() }}"
                    },
                    beforeSend: function() {
                        $('#lista-emprestimos').html('<div class="text-center p-3">Carregando...</div>');
                        $('#parcelas-container').hide().empty();
                        $('#emprestimos-container').show();
                    },
                    success: function(response) {
                        responseData = response;
                        if (response.success && response.emprestimos.length > 0) {
                            renderizarEmprestimos(response.emprestimos);
                        } else {
                            $('#lista-emprestimos').html('<div class="text-center p-3">Nenhum empréstimo encontrado</div>');
                        }
                    },
                    error: function(xhr) {
                        $('#lista-emprestimos').html('<div class="text-center p-3 text-danger">Erro ao carregar dados</div>');
                        console.error('Erro:', xhr.responseText);
                    }
                });
            }

            function renderizarEmprestimos(emprestimos) {
                var lista = $('#lista-emprestimos').empty();
                emprestimos.forEach(function(emprestimo) {
                    var valorEmprestado = parseFloat(emprestimo.valor_emprestado);
                    var valorTotal = parseFloat(emprestimo.valor_total);
                    var taxaJuros = parseFloat(emprestimo.taxa_juros_mensal);
                    var tipoJurosFormatado = emprestimo.tipo_juros === 'simples' ? 'Simples' : 'Composto';
                    var dataContratacao = new Date(emprestimo.data_contratacao).toLocaleDateString('pt-BR');

                    lista.append(`
                    <div class="list-group-item emprestimo-item" data-id="${emprestimo.id}">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">Empréstimo #${emprestimo.id}</h6>
                                <small class="text-muted">
                                    Contratado em: ${dataContratacao} | 
                                    Juros: ${tipoJurosFormatado} (${taxaJuros.toFixed(2)}%)
                                </small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-${emprestimo.status === 'ativo' ? 'success' : 'warning'}">
                                    ${emprestimo.status.toUpperCase()}
                                </span>
                                <button class="btn btn-sm btn-outline-primary ver-parcelas ms-2">
                                    Ver Parcelas
                                </button>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small>
                                <strong>Valor Emprestado:</strong> R$ ${valorEmprestado.toFixed(2)} | 
                                <strong>Total:</strong> R$ ${valorTotal.toFixed(2)}
                            </small>
                        </div>
                        <div class="parcelas-container mt-2" style="display: none;"></div>
                    </div>
                `);
                });
            }

            $(document).on('click', '.ver-parcelas', function() {
                var container = $(this).closest('.emprestimo-item').find('.parcelas-container');
                var emprestimoId = $(this).closest('.emprestimo-item').data('id');

                if (container.is(':empty')) {
                    var emprestimo = responseData.emprestimos.find(e => e.id == emprestimoId);
                    renderizarParcelas(container, emprestimo.parcelas, emprestimoId);
                }
                container.slideToggle();
            });

            function renderizarParcelas(container, parcelas, emprestimoId) {
                if (!parcelas || parcelas.length === 0) {
                    container.html('<div class="alert alert-warning p-2 my-2">Nenhuma parcela pendente</div>');
                    return;
                }

                var html = `
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Parcela</th>
                                <th>Valor</th>
                                <th>Vencimento</th>
                                <th>Status</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>`;

                parcelas.forEach(function(parcela) {
                    var valorParcela = parseFloat(parcela.valor_parcela);
                    var statusClass = {
                        'pendente': 'warning',
                        'pago': 'success',
                        'atrasado': 'danger'
                    } [parcela.status] || 'secondary';

                    var dataVencimento = new Date(parcela.data_vencimento).toLocaleDateString('pt-BR');

                    html += `
                    <tr>
                        <td>${parcela.numero_parcela}</td>
                        <td>R$ ${valorParcela.toFixed(2)}</td>
                        <td>${dataVencimento}</td>
                        <td>
                            <span class="badge bg-${statusClass}">
                                ${parcela.status.toUpperCase()}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm ${
                                parcela.status === 'pendente' ? 'btn-primary' : 
                                parcela.status === 'atrasado' ? 'btn-danger' : 
                                'btn-secondary'
                            } btn-pagar" 
                            data-id="${parcela.id}"
                            data-emprestimo-id="${emprestimoId}"
                            data-status="${parcela.status}"
                            ${parcela.status === 'pago' ? 'disabled' : ''}>
                                ${parcela.status === 'atrasado' ? 'Regularizar' : 'Pagar'}
                            </button>
                        </td>
                    </tr>`;
                });

                html += `</tbody></table></div>`;
                container.html(html);
            }

            $(document).on('click', '.btn-pagar', function() {
                var parcelaId = $(this).data('id');
                var statusParcela = $(this).data('status');
                var emprestimoId = $(this).data('emprestimo-id');

                $('#parcela_id').val(parcelaId);
                $('#emprestimo_id').val(emprestimoId);
                $('#multa_atraso').val('');

                $('#formPagar').attr('action', "{{ route('emprestimo.update', ['emprestimo' => ':id']) }}".replace(':id', emprestimoId));

                $('#multaContainer').toggle(statusParcela === 'atrasado');
                var modal = new bootstrap.Modal(document.getElementById('modalPagar'));
                modal.show();
            });

            $('#formPagar').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                var submitBtn = form.find('[type="submit"]');
                submitBtn.prop('disabled', true);

                var formData = {
                    _token: "{{ csrf_token() }}",
                    _method: 'PUT',
                    parcela_id: $('#parcela_id').val(),
                    emprestimo_id: $('#emprestimo_id').val()
                };


                var multaVal = $('#multa_atraso').val();
                if (multaVal && multaVal !== '') {
                    formData.multa_atraso = parseFloat(multaVal);
                }

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#modalPagar').modal('hide');
                        showAlert('success', response.message || 'Pagamento registrado com sucesso!');
                        if ($('#cliente_id').val()) {
                            loadEmprestimos($('#cliente_id').val());
                        }
                    },
                    error: function(xhr) {
                        var errorMsg = xhr.responseJSON?.message || 'Erro ao processar pagamento';
                        showAlert('danger', errorMsg);
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false);
                    }
                });
            });

            function showAlert(type, message) {
                $('.alert-dismissible').alert('close');
                $('.content').prepend(`
                    <div class="alert alert-${type} alert-dismissible fade show">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);
                setTimeout(() => {
                    $('.alert').alert('close');
                }, 5000);
            }
        });
    </script>
</x-app-layout>