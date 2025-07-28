@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Cabeçalho -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-sky-950">Deputados Federais</h1>
                <p class="text-gray-600 mt-1">Listagem completa dos parlamentares</p>
            </div>
            <a href="{{ route('deputies.index') }}" 
               class="inline-flex items-center text-sm text-sky-600 hover:text-sky-800 hover:underline">
                <i class="fas fa-sync-alt mr-2"></i> Limpar filtros
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow border border-gray-200 p-5 mb-6">
        <form method="GET" class="space-y-4 md:space-y-0">
            <div class="flex flex-col md:flex-row justify-between gap-4">
                <!-- Nome -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" name="name" id="name" value="{{ request('name') }}"
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md 
                                      focus:outline-none focus:ring-sky-500 focus:border-sky-500"
                               placeholder="Buscar deputado">
                    </div>
                </div>
                <!-- Partido -->
                <div>
                    <label for="political_party_id" class="block text-sm font-medium text-gray-700 mb-1">Partido</label>
                    <div>
                        <select name="political_party_id" id="political_party_id"
                                class="block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-md 
                                       focus:outline-none focus:ring-sky-500 focus:border-sky-500">
                            <option value="">Todos os partidos</option>
                            @foreach ($parties as $party)
                            <option value="{{ $party->id }}" @selected(request('political_party_id')==$party->id)>
                                {{ $party->acronym }} - {{ $party->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Botão -->
                <div class="flex items-end">
                    <button type="submit"
                            class="w-full bg-sky-600 hover:bg-sky-700 text-white px-4 py-2 rounded-md 
                                   shadow-sm transition flex items-center justify-center">
                        <i class="fas fa-filter mr-2"></i> Filtrar
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabela -->
    <div class="bg-white shadow rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-sky-950">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-sky-50 uppercase tracking-wider">
                            Deputado
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-sky-50 uppercase tracking-wider">
                            Partido
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-sky-50 uppercase tracking-wider">
                            Estado
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-sky-50 uppercase tracking-wider">
                            Despesas
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($deputies as $deputy)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <!-- Coluna Nome -->
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $deputy->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $deputy->email ?? 'Sem e-mail' }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Coluna Partido -->
                        <td class="px-6 py-4">
                            <div class="flex items-center">

                                <div class="ml-3">
                                    <div class="text-sm text-gray-900">{{ $deputy->politicalParty->name ?? 'Sem partido' }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Coluna Estado -->
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                      bg-gray-100 text-gray-800">
                                {{ $deputy->state_acronym }}
                            </span>
                        </td>

                        <!-- Coluna Ações -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex space-x-4">
                                <a href="{{ route('deputies.show', $deputy->id) }}" 
                                   class="text-green-600 hover:text-green-800 transition-colors"
                                   title="Ver despesas">
                                    <i class="fas fa-receipt"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-user-slash text-3xl text-gray-400 mb-2"></i>
                                <p>Nenhum deputado encontrado com os filtros aplicados.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginação -->
    <div class="mt-6 px-2">
        {{ $deputies->withQueryString()->links() }}
    </div>
</div>
@endsection