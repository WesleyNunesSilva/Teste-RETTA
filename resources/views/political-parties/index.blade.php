@extends('layouts.app')

@section('content')

    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-sky-950 mb-6">Partidos Políticos</h1>

        <!-- Filtros -->
        <form method="GET" class="mb-8 bg-white shadow rounded-lg p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-sky-950">Nome</label>
                    <input type="text" name="name" id="name" value="{{ request('name') }}"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                </div>

                <div>
                    <label for="acronym" class="block text-sm font-medium text-sky-950">Sigla</label>
                    <input type="text" name="acronym" id="acronym" value="{{ request('acronym') }}"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                </div>
            </div>

            <div class="mt-6 flex items-center gap-4">
                <button type="submit"
                        class="bg-sky-950 text-white px-5 py-2 rounded-md hover:bg-sky-100 transition">
                    Filtrar
                </button>
                <a href="{{ route('political-parties.index') }}"
                   class="text-gray-600 underline hover:text-gray-100 transition">
                    Limpar
                </a>
            </div>
        </form>

        <!-- Tabela -->
        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-sky-950">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-sky-100">Nome</th>
                        <th class="px-4 py-3 text-left font-semibold text-sky-100">Sigla</th>
                        <th class="px-4 py-3 text-left font-semibold text-sky-100">Número</th>
                        <th class="px-4 py-3 text-left font-semibold text-sky-100">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($parties as $party)
                        <tr>
                            <td class="px-4 py-3">{{ $party->name }}</td>
                            <td class="px-4 py-3">{{ $party->acronym }}</td>
                            <td class="px-4 py-3">{{ $party->number }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('political-parties.show', $party->id) }}"
                                   class="text-sky-600 hover:underline">Ver detalhes</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-5 text-center text-gray-500">Nenhum partido encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $parties->withQueryString()->links('pagination::tailwind') }}
        </div>
        
    </div>

@endsection
