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
                    <h3 class="text-center bg-gray-100 p-4">Cadastro Cliente</h3>
                </div>
                <form class="p-4" method="POST" action="{{ route('cliente.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nome *</label>
                            <input type="text" class="form-control {{ $errors->has('nome') ? 'is-invalid' : '' }}"
                                name="nome" placeholder="Nome" value="{{ old('nome') }}">
                            @error('nome')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Sobrenome *</label>
                            <input type="text" class="form-control {{ $errors->has('sobrenome') ? 'is-invalid' : '' }}"
                                name="sobrenome" placeholder="Sobrenome" value="{{ old('sobrenome') }}">
                            @error('sobrenome')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">CPF *</label>
                        <input type="text" class="form-control {{ $errors->has('cpf') ? 'is-invalid' : '' }}"
                            name="cpf" placeholder="CPF" value="{{ old('cpf') }}">
                        @error('cpf')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Telefone *</label>
                        <input type="text" class="form-control {{ $errors->has('telefone') ? 'is-invalid' : '' }}"
                            name="telefone" placeholder="Telefone" value="{{ old('telefone') }}">
                        @error('telefone')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Renda Mensal *</label>
                        <input type="text" class="form-control {{ $errors->has('renda') ? 'is-invalid' : '' }}"
                            name="renda" placeholder="Renda" value="{{ old('renda') }}">
                        @error('renda')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Profissão</label>
                        <input type="text" class="form-control {{ $errors->has('profissao') ? 'is-invalid' : '' }}"
                            name="profissao" placeholder="Profissão" value="{{ old('profissao') }}">
                        @error('profissao')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success me-2">Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>