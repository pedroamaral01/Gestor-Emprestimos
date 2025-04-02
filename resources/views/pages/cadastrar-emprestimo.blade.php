<x-app-layout>
    <div class="container-fluid d-flex w-75">
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
                    <h3 class="text-center bg-gray-100 p-4">Cadastro Emprestimo</h3>
                </div>
                <form class="p-4" method="POST" action="{{ route('emprestimo.store') }}">
                    @csrf
                    <!-- Cliente -->
                    <div class="col-md-6 mb-3">
                        <label for="cliente_id" class="form-label">Cliente *</label>
                        <select class="form-select" id="cliente_id" name="cliente_id">
                            <option value="" selected disabled>Selecione um cliente</option>
                            @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                            @endforeach
                            @error('cliente_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </select>
                    </div>

                    <div class="row">
                        <!-- Valor do Empréstimo -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Valor do Empréstimo *</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="number" step="0.01"
                                    class="form-control {{ $errors->has('valor') ? 'is-invalid' : '' }}"
                                    name="valor" placeholder="0,00" value="{{ old('valor') }}">
                                @error('valor')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Quantidade de Parcelas -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Quantidade de Parcelas *</label>
                            <input type="number"
                                class="form-control {{ $errors->has('qtd_parcelas') ? 'is-invalid' : '' }}"
                                name="qtd_parcelas" value="{{ old('qtd_parcelas', 1) }}" min="1">
                            @error('qtd_parcelas')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Tipo de Juros (Radio) com Porcentagem -->
                    <div class="mb-4">
                        <div class="row align-items-end">
                            <!-- Coluna dos Tipos de Juros -->
                            <div class="col-md-6">
                                <label class="form-label">Tipo de Juros *</label>
                                <div class="mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tipo_juros"
                                            id="jurosSimples" value="simples" {{ old('tipo_juros') == 'simples' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="jurosSimples">Simples</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tipo_juros"
                                            id="jurosComposto" value="composto" {{ old('tipo_juros') == 'composto' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="jurosComposto">Composto</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Coluna do Percentual -->
                            <div class="col-md-6">
                                <label for="percentualJuros" class="form-label">Percentual de Juros (%) *</label>
                                <div class="input-group mt-2">
                                    <input type="number" class="form-control" name="percentual_juros" id="percentualJuros"
                                        placeholder="0.00" min="0" step="0.01" value="{{ old('percentual_juros') }}">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>

                        @error('tipo_juros')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @error('percentual_juros')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <!-- Data Contratação -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Data de Contratação *</label>
                            <input type="date"
                                class="form-control {{ $errors->has('data_contratacao') ? 'is-invalid' : '' }}"
                                name="data_contratacao"
                                value="{{ old('data_contratacao', now()->format('Y-m-d')) }}">
                            @error('data_contratacao')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Data Vencimento Primeira Parcela -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Data Vencimento 1ª Parcela *</label>
                            <input type="date"
                                class="form-control {{ $errors->has('data_vencimento_primeira_parcela') ? 'is-invalid' : '' }}"
                                name="data_vencimento_primeira_parcela"
                                value="{{ old('data_vencimento_primeira_parcela', now()->addDays(30)->format('Y-m-d')) }}">
                            @error('data_vencimento_primeira_parcela')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Finalidade -->
                    <div class="mb-3">
                        <label class="form-label">Finalidade *</label>
                        <textarea class="form-control {{ $errors->has('finalidade') ? 'is-invalid' : '' }}"
                            name="finalidade" rows="3">{{ old('finalidade') }}</textarea>
                        @error('finalidade')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Garantia (Opcional) -->
                    <div class="card mb-4">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center pe-3 position-relative"
                            data-bs-toggle="collapse"
                            data-bs-target="#garantiaCollapse"
                            aria-expanded="false"
                            aria-controls="garantiaCollapse"
                            style="cursor: pointer">
                            <h5 class="mb-0 d-flex align-items-center">
                                <span class="badge bg-secondary me-2">OPCIONAL</span>
                                Garantia
                            </h5>
                            <div class="d-flex align-items-center">
                                <span class="text-muted small me-2 d-none d-sm-inline">Expandir</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>

                        <div class="collapse" id="garantiaCollapse">
                            <div class="card-body">
                                <!-- Tipo Garantia -->
                                <div class="mb-3">
                                    <label class="form-label">Tipo de Garantia</label>
                                    <select class="form-control {{ $errors->has('garantia_tipo') ? 'is-invalid' : '' }}"
                                        name="garantia_tipo">
                                        <option value="">Selecione...</option>
                                        @foreach($garantia as $key => $value)
                                        <option value="{{ $key }}" {{ old('garantia_tipo') == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('garantia_tipo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Descrição Garantia -->
                                <div class="mb-3">
                                    <label class="form-label">Descrição</label>
                                    <textarea class="form-control {{ $errors->has('garantia_descricao') ? 'is-invalid' : '' }}"
                                        name="garantia_descricao" rows="2">{{ old('garantia_descricao') }}</textarea>
                                    @error('garantia_descricao')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Valor Avaliado -->
                                <div class="mb-3">
                                    <label class="form-label">Valor Avaliado</label>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="number" step="0.01" class="form-control {{ $errors->has('garantia_valor_avaliado') ? 'is-invalid' : '' }}"
                                            name="garantia_valor_avaliado" value="{{ old('garantia_valor_avaliado') }}">
                                        @error('garantia_valor_avaliado')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @push('styles')
                    <style>
                        [data-bs-toggle="collapse"] {
                            transition: background-color 0.2s ease;
                        }

                        [data-bs-toggle="collapse"]:hover {
                            background-color: #f8f9fa !important;
                        }

                        [aria-expanded="true"] .fa-chevron-down {
                            transform: rotate(180deg);
                            transition: transform 0.3s ease;
                        }

                        .hover-opacity-10:hover {
                            opacity: 0.1 !important;
                        }
                    </style>
                    @endpush

                    <!-- Botões -->
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-primary" id="btnSimular">
                            <i class="fas fa-calculator me-1"></i> Simular
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i> Cadastrar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#btnSimular').click(function() {
                // Collect form data
                const formData = {
                    cliente_id: $('#cliente_id').val(),
                    valor: $('input[name="valor"]').val(),
                    qtd_parcelas: $('input[name="qtd_parcelas"]').val(),
                    tipo_juros: $('input[name="tipo_juros"]:checked').val(),
                    percentual_juros: $('input[name="percentual_juros"]').val(),
                    data_contratacao: $('input[name="data_contratacao"]').val(),
                    data_vencimento_primeira_parcela: $('input[name="data_vencimento_primeira_parcela"]').val(),
                    garantia_tipo: $('select[name="garantia_tipo"]').val(),
                    garantia_valor_avaliado: $('input[name="garantia_valor_avaliado"]').val(),
                    _token: '{{ csrf_token() }}'
                };

                // Make AJAX request
                $.ajax({
                    url: '{{ route("emprestimo.calcular-risco") }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // Prepare HTML content
                        let htmlContent = `
                        <div class="text-start">
                            <p><strong>Nível de Risco:</strong> ${response.nivel_risco}</p>
                            <p><strong>Taxa de Juros Recomendada:</strong> ${response.taxa_juros}%</p>
                    `;

                        // Add recommendation if available
                        if (response.recomendacao) {
                            htmlContent += `<p><strong>Recomendação:</strong> ${response.recomendacao}</p>`;
                        }

                        // Add details section if available
                        if (response.detalhes) {
                            htmlContent += `
                            <div class="mt-3">
                                <h6>Detalhes:</h6>
                                <ul>
                        `;

                            if (response.detalhes.score_risco) {
                                htmlContent += `<li>Score: ${response.detalhes.score_risco}</li>`;
                            }

                            if (response.detalhes.comprometimento_renda) {
                                htmlContent += `<li>Comprometimento da renda: ${response.detalhes.comprometimento_renda}</li>`;
                            }

                            if (response.detalhes.relacao_garantia) {
                                htmlContent += `<li>Relação garantia: ${response.detalhes.relacao_garantia}</li>`;
                            }

                            htmlContent += `
                                </ul>
                            </div>
                        `;
                        }

                        htmlContent += `</div>`;

                        // Show result in SweetAlert
                        Swal.fire({
                            title: 'Resultado da Simulação',
                            html: htmlContent,
                            icon: (response.nivel_risco === 'Alto' || response.nivel_risco === 'Muito Alto') ?
                                'warning' : 'success',
                            customClass: {
                                popup: 'text-left'
                            }
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'Ocorreu um erro ao calcular o risco';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire('Erro', errorMessage, 'error');
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>