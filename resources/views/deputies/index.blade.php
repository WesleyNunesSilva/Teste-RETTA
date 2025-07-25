@extends('layouts.app')

@section('content')

    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-sky-950 mb-6">Deputados</h1>

        <!-- Filtros -->
        <form method="GET" class="mb-8 bg-white shadow rounded-lg p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-sky-950">Nome</label>
                    <input type="text" name="name" id="name" value="{{ request('name') }}"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                </div>

                <div>
                    <label for="state_acronym" class="block text-sm font-medium text-sky-950">Estado</label>
                    <input type="text" name="state_acronym" id="state_acronym" value="{{ request('state_acronym') }}"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                </div>

                <div>
                    <label for="political_party_id" class="block text-sm font-medium text-sky-950">Partido</label>
                    <select name="political_party_id" id="political_party_id"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500">
                        <option value="">Todos</option>
                        @foreach ($parties as $party)
                            <option value="{{ $party->id }}"
                                @selected(request('political_party_id') == $party->id)>
                                {{ $party->acronym }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-6 flex items-center gap-4">
                <button type="submit"
                        class="bg-sky-950 text-white px-5 py-2 rounded-md hover:bg-sky-100 transition">
                    Filtrar
                </button>
                <a href="{{ route('deputies.index') }}"
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
                        <th class="px-4 py-3 text-left font-semibold text-sky-100">Estado</th>
                        <th class="px-4 py-3 text-left font-semibold text-sky-100">Partido</th>
                        <th class="px-4 py-3 text-left font-semibold text-sky-100">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($deputies as $deputy)
                        <tr>
                            <td class="px-4 py-3">{{ $deputy->name }}</td>
                            <td class="px-4 py-3">{{ $deputy->state_acronym }}</td>
                            <td class="px-4 py-3">{{ $deputy->politicalParty->acronym ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('deputies.show', $deputy->id) }}"
                                class="text-sky-600 hover:underline">Ver detalhes</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-5 text-center text-gray-500">Nenhum deputado encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        <div class="mt-8">
            {{ $deputies->withQueryString()->links() }}
        </div>
    </div>
@endsection