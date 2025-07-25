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

<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Deputado: {{ $deputy->name }}</h1>

    <div class="mb-8 bg-gray-100 p-4 rounded shadow">
        <p><strong>Partido:</strong> {{ $deputy->politicalParty?->acronym ?? 'Indefinido' }}</p>
        <p><strong>Estado:</strong> {{ $deputy->state_acronym }}</p>
        <p><strong>Email:</strong> 
            @if ($deputy->email)
                <a href="mailto:{{ $deputy->email }}" class="text-blue-600 underline">{{ $deputy->email }}</a>
            @else
                <span class="text-gray-500">Não informado</span>
            @endif
        </p>
    </div>

    <h2 class="text-2xl font-semibold mb-4">Despesas</h2>

    <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <input type="text" name="supplier_name" value="{{ $filters['supplier_name'] ?? '' }}" placeholder="Fornecedor" class="border p-2 rounded w-full" />
        
        <input type="text" name="expense_type" value="{{ $filters['expense_type'] ?? '' }}" placeholder="Tipo de despesa" class="border p-2 rounded w-full" />

        <select name="year" class="border p-2 rounded w-full">
            <option value="">Ano</option>
            @foreach(range(now()->year, 2019) as $year)
                <option value="{{ $year }}" @selected(isset($filters['year']) && $filters['year'] == $year)>{{ $year }}</option>
            @endforeach
        </select>

        <select name="month" class="border p-2 rounded w-full">
            <option value="">Mês</option>
            @foreach(range(1, 12) as $m)
                <option value="{{ $m }}" @selected(isset($filters['month']) && $filters['month'] == $m)>{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}</option>
            @endforeach
        </select>

        <div class="col-span-1 sm:col-span-2 md:col-span-4 flex justify-end gap-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filtrar</button>
            <a href="{{ route('deputies.show', $deputy->id) }}" class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">Limpar</a>
        </div>
    </form>

    @if ($expenses->count())
        <div class="overflow-auto rounded shadow border">
            <table class="min-w-full bg-white">
                <thead class="bg-sky-950 text-left">
                    <tr>
                        <th class="px-4 py-2 border text-sky-100">
                            <a href="{{ sortUrl('year') }}" class="flex items-center gap-1 hover:underline">
                                Ano
                                @if ($currentSortBy !== 'year')
                                <x-heroicon-o-chevron-up-down class="w-4 h-4 text-gray-200" />
                                @elseif ($currentDirection === 'asc')
                                    <x-heroicon-o-chevron-up class="w-5 h-5 text-gray-200" />
                                @else
                                    <x-heroicon-o-chevron-down class="w-5 h-5 text-gray-200" />
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-2 border text-sky-100">
                            <a href="{{ sortUrl('month') }}" class="flex items-center gap-1 hover:underline">
                                Mes
                                @if ($currentSortBy !== 'month')
                                <x-heroicon-o-chevron-up-down class="w-4 h-4 text-gray-200" />
                                @elseif ($currentDirection === 'asc')
                                    <x-heroicon-o-chevron-up class="w-5 h-5 text-gray-200" />
                                @else
                                    <x-heroicon-o-chevron-down class="w-5 h-5 text-gray-200" />
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-2 border text-sky-100">
                            Fornecedor 
                        </th>
                        <th class="px-4 py-2 border text-sky-100">
                            Tipo
                        </th>
                        <th class="px-4 py-2 border text-sky-100 text-right">
                            <a href="{{ sortUrl('amount') }}" class="flex items-center gap-1 hover:underline">
                                Valor
                                @if ($currentSortBy !== 'amount')
                                <x-heroicon-o-chevron-up-down class="w-4 h-4 text-gray-200" />
                                @elseif ($currentDirection === 'asc')
                                    <x-heroicon-o-chevron-up class="w-5 h-5 text-gray-200" />
                                @else
                                    <x-heroicon-o-chevron-down class="w-5 h-5 text-gray-200" />
                                @endif
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($expenses as $expense)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border">{{ $expense->year }}</td>
                            <td class="px-4 py-2 border">{{ str_pad($expense->month, 2, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-4 py-2 border">{{ $expense->supplier_name }}</td>
                            <td class="px-4 py-2 border">{{ $expense->expense_type }}</td>
                            <td class="px-4 py-2 border text-right">{{ number_format($expense->amount, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $expenses->appends(request()->query())->links() }}
        </div>
    @else
        <p class="text-gray-600 mt-4">Nenhuma despesa registrada para este deputado com os filtros aplicados.</p>
    @endif
</div>
@endsection
