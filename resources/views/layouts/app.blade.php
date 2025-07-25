<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>{{ $title ?? 'Sistema de Deputados' }}</title>
    <meta name="description" content="Visualização e filtros de deputados federais.">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Fonte (opcional) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <!-- Estilo global (opcional) -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">
    <header class="bg-sky-950 text-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-xl font-semibold">Sistema de Deputados</h1>
            <nav>
                <a href="{{ route('deputies.index') }}" class="text-white hover:underline">Deputados</a>
                {{-- Adicione outros links aqui se necessário --}}
            </nav>
        </div>
    </header>

    <main class="max-w-7xl mx-auto py-6 px-4">
        @yield('content')
    </main>
</body>
</html>
