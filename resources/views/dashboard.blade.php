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
      <x-slot name="header">
        <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
          <div class="container">
            <a class="navbar-brand" href="#">Dashboard</a>
          </div>
        </nav>
      </x-slot>

      <div class="container py-5">
        <div class="row justify-content-center">
          <div class="col-md-8">
            <div class="card shadow-sm">
              <div class="card-body">
                <h5 class="card-title">{{ __("Você está logado!") }}</h5>
                <a href="{{ route('cliente.create') }}" class="btn btn-primary mt-3">Criar Cliente</a>
                <a href="{{ route('emprestimo.create') }}" class="btn btn-primary mt-3">Criar Emprestimo<a>
              </div>
            </div>
          </div>
        </div>
      </div>
</x-app-layout>