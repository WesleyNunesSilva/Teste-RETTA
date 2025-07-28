@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Cabeçalho -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-sky-950 mb-2">Partidos Políticos</h1>
        <p class="text-gray-600">Listagem completa dos partidos com representação</p>
    </div>

    <!-- Filtros -->
    <form method="GET" class="mb-8 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                <div class="relative rounded-md shadow-sm">
                    <input type="text" name="name" id="name" value="{{ request('name') }}"
                           class="block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-sky-500 focus:border-sky-500"
                           placeholder="Buscar por nome">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>

            <div>
                <label for="acronym" class="block text-sm font-medium text-gray-700 mb-1">Sigla</label>
                <div class="relative rounded-md shadow-sm">
                    <input type="text" name="acronym" id="acronym" value="{{ request('acronym') }}"
                           class="block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-sky-500 focus:border-sky-500"
                           placeholder="Buscar por sigla">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-font text-gray-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex items-center gap-4">
            <button type="submit"
                    class="bg-sky-600 hover:bg-sky-700 text-white px-5 py-2 rounded-md shadow-sm transition flex items-center">
                <i class="fas fa-filter mr-2"></i> Filtrar
            </button>
            <a href="{{ route('political-parties.index') }}"
               class="text-gray-600 hover:text-gray-800 underline transition">
                Limpar filtros
            </a>
        </div>
    </form>

    <!-- Tabela -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-sky-950">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-sky-50 uppercase tracking-wider">
                            Nome
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-sky-50 uppercase tracking-wider">
                            Sigla
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-sky-50 uppercase tracking-wider">
                            Número
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-sky-50 uppercase tracking-wider">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($parties as $party)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $party->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $party->acronym }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $party->number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('political-parties.show', $party->id) }}" 
                               class="text-sky-600 hover:text-sky-800 transition-colors flex items-center">
                                <i class="fas fa-eye mr-1"></i> Detalhes
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-flag text-3xl text-gray-400 mb-2"></i>
                                <p>Nenhum partido encontrado com os filtros aplicados.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginação -->
    <div class="mt-6">
        {{ $parties->onEachSide(1)->links() }}
    </div>
</div>
@endsection