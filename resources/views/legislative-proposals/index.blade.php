@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Cabeçalho -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-sky-950 mb-2">Proposições Legislativas</h1>
        <p class="text-gray-600">Listagem completa das proposições</p>
    </div>

    <!-- Tabela -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-sky-950">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-sky-50 uppercase tracking-wider">
                            ID
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-sky-50 uppercase tracking-wider">
                            Tipo
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-sky-50 uppercase tracking-wider">
                            Número
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-sky-50 uppercase tracking-wider">
                            Ano
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-sky-50 uppercase tracking-wider">
                            Resumo
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-sky-50 uppercase tracking-wider">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($proposals as $proposal)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $proposal->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $proposal->type_acronym }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $proposal->number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $proposal->year ?: '—' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate" title="{{ $proposal->summary }}">
                            {{ Str::limit($proposal->summary, 80) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-sky-600">
                            <a href="{{ route('legislative-proposals.show', $proposal->id) }}" 
                               class="hover:text-sky-800 hover:underline flex items-center">
                                <i class="fas fa-eye mr-1"></i> Ver detalhes
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-file-alt text-3xl text-gray-400 mb-2"></i>
                                <p>Nenhuma proposição encontrada.</p>
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
        {{ $proposals->links() }}
    </div>
</div>
@endsection