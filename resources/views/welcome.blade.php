<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestor de Empréstimos</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        .hero-section {
            background-color: #495057;
            min-height: 70vh;
        }
    </style>
</head>

<body>
    <!-- Cabeçalho -->
    <header class="bg-white shadow-sm py-3">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <a class="navbar-brand fw-bold text-primary fs-4" href="#">
                    <i class="bi bi-cash-stack me-2"></i>Controle de Empréstimo
                </a>

                <nav>
                    @if (Route::has('login'))
                    <div class="d-flex gap-2">
                        @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary px-4">
                            <i class="bi bi-speedometer2 me-2"></i>Painel
                        </a>
                        @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary px-4">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Entrar
                        </a>

                        @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-primary px-4">
                            <i class="bi bi-person-plus me-2"></i>Cadastrar
                        </a>
                        @endif
                        @endauth
                    </div>
                    @endif
                </nav>
            </div>
        </div>
    </header>

    <!-- Seção Hero -->
    <section class="hero-section d-flex align-items-center">
        <div class="container text-center py-5">
            <h1 class="display-4 fw-bold mb-4 text-white">
                Controle de Empréstimos <span class="text-primary">Simplificado</span>
            </h1>
            <p class="lead text-white mb-5">
                Gerencie todos os seus empréstimos em um único lugar com nossa plataforma intuitiva e poderosa.
            </p>
            <div class="d-flex justify-content-center gap-3">
                @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-lg px-5">
                    <i class="bi bi-speedometer2 me-2"></i>Acessar Painel
                </a>
                @else
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-5">
                    <i class="bi bi-rocket me-2"></i>Comece Agora
                </a>
                <a href="#features" class="btn btn-outline-light btn-lg px-5">
                    <i class="bi bi-info-circle me-2"></i>Saiba Mais
                </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Recursos -->
    <section id="features" class="pb-5 bg-light">
        <div class="container py-5">
            <h2 class="text-center mb-5 fw-bold">Recursos Principais</h2>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-graph-up fs-1 text-primary mb-3"></i>
                            <h3 class="h5">Controle Total</h3>
                            <p class="text-muted">Acompanhe todos os empréstimos, pagamentos e inadimplências em tempo real.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-file-earmark-bar-graph fs-1 text-primary mb-3"></i>
                            <h3 class="h5">Relatórios Detalhados</h3>
                            <p class="text-muted">Gere relatórios personalizados para análise financeira.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-shield-exclamation fs-1 text-primary mb-3"></i>
                            <h3 class="h5">Cálculo de Risco</h3>
                            <p class="text-muted">Avaliação automática do risco de empréstimos com base em histórico e garantias.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Rodapé -->
    <footer class="bg-dark text-white py-4 mt-auto">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-cash-stack fs-4 text-primary me-2"></i>
                        <h3 class="h5 mb-0">Controle de Empréstimo</h3>
                    </div>
                    <p class="text-white-50 mb-md-0 mt-2">Solução completa para gestão de empréstimos.</p>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <div class="d-flex justify-content-md-end gap-3">
                        <a href="https://www.instagram.com/p_amaral1/" class="text-white"><i class="bi bi-instagram"></i></a>
                        <a href="https://www.linkedin.com/in/pedro-henrique-amaral-estevao/" class="text-white"><i class="bi bi-linkedin"></i></a>
                    </div>
                    <p class="text-white-50 mb-md-0 mt-2">© {{ date('Y') }} Todos os direitos reservados</p>
                </div>
            </div>
        </div>
    </footer>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
    </script>
</body>

</html>