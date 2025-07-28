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
    
    <!-- Ícones (Font Awesome) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 text-gray-800 flex flex-col min-h-screen">
    <!-- Header -->
    <header class="bg-sky-950 text-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('deputies.index') }}" class="flex items-center space-x-2">
                        <i class="fas fa-landmark text-xl text-sky-300"></i>
                        <span class="text-xl font-bold tracking-tight">RETTA</span>
                    </a>
                </div>

                <!-- Navegação Desktop -->
                <nav class="hidden md:flex space-x-8">
                    <a href="{{ route('deputies.index') }}" 
                       class="text-white hover:text-sky-200 px-3 py-2 rounded-md text-sm font-medium transition flex items-center space-x-1">
                        <i class="fas fa-users w-4"></i>
                        <span>Deputados</span>
                    </a>
                    <a href="{{ route('political-parties.index') }}" 
                       class="text-white hover:text-sky-200 px-3 py-2 rounded-md text-sm font-medium transition flex items-center space-x-1">
                        <i class="fas fa-flag w-4"></i>
                        <span>Partidos</span>
                    </a>
                    <a href="{{ route('legislative-proposals.index') }}" 
                       class="text-white hover:text-sky-200 px-3 py-2 rounded-md text-sm font-medium transition flex items-center space-x-1">
                        <i class="fas fa-file-alt w-4"></i>
                        <span>Proposições</span>
                    </a>
                </nav>

                <!-- Mobile menu button (opcional) -->
                <div class="md:hidden flex items-center">
                    <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white focus:outline-none">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- Page Content -->
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-sky-950 text-white py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <span class="text-sm">© {{ date('Y') }} RETTA - Dados Abertos da Câmara</span>
                </div>
                <div class="flex space-x-4">
                    <a href="https://github.com/WesleyNunesSilva" target="_blank" class="text-gray-300 hover:text-white">
                        <i class="fab fa-github"></i>
                    </a>

                </div>
            </div>
        </div>
    </footer>
</body>
</html>