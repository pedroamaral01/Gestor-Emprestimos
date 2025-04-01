<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
    <div class="container">


        <!-- Hamburger menu button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation Content -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <!-- Left Side Navigation Links -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('pages.dashboard')) active @endif" href="{{ route('pages.dashboard') }}">
                        <i class="fas fa-chart-line me-1"></i> {{ __('Dashboard') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('cliente.create')) active @endif" href="{{ route('cliente.create') }}">
                        <i class="fas fa-user-plus me-1"></i> Cadastrar Cliente
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('emprestimo.create')) active @endif" href="{{ route('emprestimo.create') }}">
                        <i class="fas fa-hand-holding-usd me-1"></i> Cadastrar Empréstimo
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('emprestimo.edit')) active @endif" href="{{ route('emprestimo.edit') }}">
                        <i class="fas fa-file-invoice-dollar me-1"></i> Pagamento
                    </a>
                </li>
            </ul>

            <!-- Right Side Settings Dropdown -->
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-2"></i>
                        {{ Auth::user()->name }}
                        <svg class="ms-1" width="16" height="16" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user-cog me-2"></i> {{ __('Profile') }}
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i> {{ __('Log Out') }}
                                </a>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>