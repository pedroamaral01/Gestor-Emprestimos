<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fontes -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Assets via Vite -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    <style>
        :root {
            font-family: 'Figtree', sans-serif;
        }
        .auth-card {
            width: 100%;
            max-width: 400px;
            border-radius: 0.75rem;
        }
        .user-icon {
            width: 80px;
            height: 80px;
            transition: transform 0.3s ease;
        }
        .user-icon:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body class="font-sans bg-gray-100">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="auth-card card p-4 shadow-lg bg-white">
            <!-- Cabeçalho com ícone -->
            <div class="text-center mb-4">
                <a href="/" class="d-inline-block text-decoration-none">
                    <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" 
                         alt="User Icon" 
                         class="user-icon"
                         title="Página inicial">
                </a>
            </div>
            
            <!-- Slot principal para conteúdo -->
            {{ $slot }}
            
        </div>
    </div>
</body>
</html>