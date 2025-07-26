@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-semibold mb-4">{{ $party->name }} ({{ $party->acronym }})</h2>

    <h3 class="text-lg font-medium mb-2">Deputados Vinculados:</h3>

    <ul class="bg-white shadow rounded-lg divide-y divide-gray-200">
        @forelse($party->deputies as $deputy)
            <li class="px-4 py-3 hover:bg-gray-50 flex justify-between">
                <span>{{ $deputy->name }}</span>
                <a href="{{ route('deputies.show', $deputy) }}" class="text-blue-600 hover:underline">Ver detalhes</a>
            </li>
        @empty
            <li class="px-4 py-3 text-gray-500">Nenhum deputado encontrado para este partido.</li>
        @endforelse
    </ul>
@endsection
