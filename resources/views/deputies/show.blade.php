@extends('layouts.app')

@section('content')
@php
$currentSortBy = request('sort_by');
$currentDirection = request('sort_direction', 'asc');
$direction = ($currentSortBy === 'year' && $currentDirection === 'asc') ? 'desc' : 'asc';

function sortUrl($field) {
    $query = array_merge(request()->query(), [
        'sort_by' => $field,
        'sort_direction' => (request('sort_by') === $field && request('sort_direction') === 'asc') ? 'desc' : 'asc',
        'page' => 1,
    ]);
    return url()->current() . '?' . http_build_query($query);
}
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Cabeçalho -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-sky-950 mb-2">Deputado: {{ $deputy->name }}</h1>
        
        <div class="bg-sky-50 rounded-lg p-5 shadow-sm border border-sky-100">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Partido</p>
                    <p class="font-medium text-gray-900">{{ $deputy->politicalParty?->acronym ?? 'Indefinido' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Estado</p>
                    <p class="font-medium text-gray-900">{{ $deputy->state_acronym }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Email</p>
                    @if ($deputy->email)
                        <a href="mailto:{{ $deputy->email }}" class="font-medium text-sky-600 hover:text-sky-800 hover:underline">{{ $deputy->email }}</a>
                    @else
                        <p class="text-gray-500">Não informado</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros de Despesas -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-sky-950 mb-4">Despesas</h2>
        
        <form method="GET" class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label for="supplier_name" class="block text-sm font-medium text-gray-700 mb-1">Fornecedor</label>
                    <input type="text" name="supplier_name" id="supplier_name" 
                           value="{{ $filters['supplier_name'] ?? '' }}" 
                           placeholder="Nome do fornecedor"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500">
                </div>
                
                <div>
                    <label for="expense_type" class="block text-sm font-medium text-gray-700 mb-1">Tipo de despesa</label>
                    <input type="text" name="expense_type" id="expense_type" 
                           value="{{ $filters['expense_type'] ?? '' }}" 
                           placeholder="Tipo de despesa"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500">
                </div>
                
                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Ano</label>
                    <select name="year" id="year" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500">
                        <option value="">Todos</option>
                        @foreach(range(now()->year, 2019) as $year)
                            <option value="{{ $year }}" @selected(isset($filters['year']) && $filters['year'] == $year)>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="month" class="block text-sm font-medium text-gray-700 mb-1">Mês</label>
                    <select name="month" id="month" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500">
                        <option value="">Todos</option>
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" @selected(isset($filters['month']) && $filters['month'] == $m)>{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="mt-4 flex justify-end gap-2">
                <button type="submit" class="bg-sky-600 hover:bg-sky-700 text-white px-4 py-2 rounded-md shadow-sm transition flex items-center">
                    <i class="fas fa-filter mr-2"></i> Filtrar
                </button>
                <a href="{{ route('deputies.show', $deputy->id) }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-gray-700 hover:bg-gray-50 transition">
                    Limpar
                </a>
            </div>
        </form>
    </div>

    <!-- Tabela de Despesas -->
    @if ($expenses->count())
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden mb-8">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-sky-950">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-sky-50 uppercase tracking-wider">
                                <a href="{{ sortUrl('year') }}" class="flex items-center gap-1 group">
                                    Ano
                                    @if ($currentSortBy !== 'year')
                                        <i class="fas fa-sort text-gray-400 group-hover:text-gray-600"></i>
                                    @elseif ($currentDirection === 'asc')
                                        <i class="fas fa-sort-up text-gray-600"></i>
                                    @else
                                        <i class="fas fa-sort-down text-gray-600"></i>
                                    @endif
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-sky-50 uppercase tracking-wider">
                                <a href="{{ sortUrl('month') }}" class="flex items-center gap-1 group ">
                                    Mês
                                    @if ($currentSortBy !== 'month')
                                        <i class="fas fa-sort text-gray-400 group-hover:text-gray-600"></i>
                                    @elseif ($currentDirection === 'asc')
                                        <i class="fas fa-sort-up text-gray-600"></i>
                                    @else
                                        <i class="fas fa-sort-down text-gray-600"></i>
                                    @endif
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-sky-50 uppercase tracking-wider">
                                Fornecedor
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-sky-50 uppercase tracking-wider">
                                Tipo
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-sky-50 uppercase tracking-wider">
                                <a href="{{ sortUrl('amount') }}" class="flex items-center gap-1 group">
                                    Valor
                                    @if ($currentSortBy !== 'amount')
                                        <i class="fas fa-sort text-gray-400 group-hover:text-gray-600"></i>
                                    @elseif ($currentDirection === 'asc')
                                        <i class="fas fa-sort-up text-gray-600"></i>
                                    @else
                                        <i class="fas fa-sort-down text-gray-600"></i>
                                    @endif
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($expenses as $expense)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $expense->year }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ str_pad($expense->month, 2, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $expense->supplier_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $expense->expense_type }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                R$ {{ number_format($expense->amount, 2, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Proposições Legislativas -->
        @if ($deputy->legislativeProposals->count())
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-sky-950 mb-4">Proposições Apresentadas</h2>
            
            <div class="grid gap-4">
                @foreach ($deputy->legislativeProposals as $proposal)
                <div class="bg-white border border-gray-200 rounded-lg p-5 shadow-sm hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="text-sm text-sky-600 mb-1">
                                {{ $proposal->type_acronym }} {{ $proposal->number }}/{{ $proposal->year }}
                            </div>
                            <h3 class="font-medium text-gray-900 mb-2">
                                {{ \Illuminate\Support\Str::limit($proposal->summary, 120) }}
                            </h3>
                        </div>
                        <a href="{{ route('legislative-proposals.show', $proposal->id) }}"
                           class="text-sm text-sky-600 hover:text-sky-800 hover:underline flex items-center">
                            <i class="fas fa-chevron-right ml-2"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Paginação -->
        <div class="mt-6">
            {{ $expenses->appends(request()->query())->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
            <i class="fas fa-receipt text-3xl text-gray-400 mb-3"></i>
            <p class="text-gray-600">Nenhuma despesa registrada para este deputado com os filtros aplicados.</p>
        </div>
    @endif
</div>
@endsection