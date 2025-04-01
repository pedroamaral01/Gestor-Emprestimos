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
                    <h3 class="text-center bg-gray-100 p-4">Pagamento </h3>
                </div>
                <form class="p-4" method="POST" action="{{ route('emprestimo.store') }}">
                    @csrf
                    <!-- Cliente -->
                    <div class="col-md-6 mb-3">
                        <label for="cliente_id" class="form-label">Cliente *</label>
                        <select class="form-select" id="cliente_id" name="cliente_id" required>
                            <option value="" selected disabled>Selecione um cliente</option>
                            @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                            @endforeach
                            @error('cliente_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </select>
                    </div>


                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Pagar
                    </button>
            </div>
            </form>
        </div>
    </div>
    </div>

</x-app-layout>