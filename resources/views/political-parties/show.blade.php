@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Cabeçalho -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-sky-950 mb-2">{{ $party->name }} <span class="font-normal">({{ $party->acronym }})</span></h1>
        <p class="text-gray-600">Deputados vinculados a este partido</p>
    </div>

    <!-- Lista de deputados -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-sky-950">
            <h3 class="text-lg font-medium text-sky-50">Deputados Vinculados</h3>
        </div>
        
        <ul class="divide-y divide-gray-200">
            @forelse($party->deputies as $deputy)
                <li class="px-6 py-4 hover:bg-gray-50 transition-colors flex justify-between items-center">
                    <span class="text-gray-900">{{ $deputy->name }}</span>
                    <a href="{{ route('deputies.show', $deputy) }}" 
                       class="text-sky-600 hover:text-sky-800 transition-colors flex items-center">
                        <i class="fas fa-eye mr-1"></i> Ver detalhes
                    </a>
                </li>
            @empty
                <li class="px-6 py-8 text-center text-gray-500">
                    <div class="flex flex-col items-center justify-center">
                        <i class="fas fa-user-times text-3xl text-gray-400 mb-2"></i>
                        <p>Nenhum deputado encontrado para este partido.</p>
                    </div>
                </li>
            @endforelse
        </ul>
    </div>
</div>
@endsection