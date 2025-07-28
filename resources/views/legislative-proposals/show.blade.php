@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <!-- Cabeçalho -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-sky-950 mb-2">Detalhes da Proposição Legislativa</h1>
        <p class="text-gray-600">Informações completas sobre esta proposição</p>
    </div>

    <!-- Card principal -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6 space-y-6">
        <!-- Grid de informações -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="border-b border-gray-100 pb-4">
                <p class="text-sm font-medium text-gray-500 mb-1">Sigla do Tipo</p>
                <p class="text-lg font-semibold text-gray-900">{{ $proposal->type_acronym }}</p>
            </div>

            <div class="border-b border-gray-100 pb-4">
                <p class="text-sm font-medium text-gray-500 mb-1">Número</p>
                <p class="text-lg font-semibold text-gray-900">{{ $proposal->number }}</p>
            </div>

            <div class="border-b border-gray-100 pb-4">
                <p class="text-sm font-medium text-gray-500 mb-1">Ano</p>
                <p class="text-lg font-semibold text-gray-900">{{ $proposal->year }}</p>
            </div>

            <div class="border-b border-gray-100 pb-4">
                <p class="text-sm font-medium text-gray-500 mb-1">Deputado Relacionado</p>
                <p class="text-lg font-semibold text-gray-900">
                    @if ($deputy)
                    <a href="{{ route('deputies.show', $deputy->deputy_id) }}" 
                       class="text-sky-600 hover:text-sky-800 hover:underline transition-colors">
                       {{ $deputy->name }}
                    </a>
                @else
                    <span class="text-gray-500">Não vinculado</span>
                @endif
                </p>
            </div>
        </div>

        <!-- Card da Ementa -->
        <div class="bg-sky-50 rounded-lg p-5 border border-sky-100">
            <p class="text-sm font-medium text-sky-800 mb-3">Ementa (Resumo)</p>
            <p class="text-gray-800 leading-relaxed">
                {{ $proposal->summary }}
            </p>
        </div>

        <!-- Autores -->
        @if ($proposal->authors->count())
            <div>
                <p class="text-sm font-medium text-sky-800 mb-3">Autores</p>
                <ul class="space-y-2">
                    @foreach ($proposal->authors as $author)
                        <li class="flex items-start">
                            <span class="flex items-center justify-center h-5 w-5 rounded-full bg-sky-100 text-sky-800 mr-3 mt-0.5">
                                <i class="fas fa-user text-xs"></i>
                            </span>
                            <span class="text-gray-800">
                                @if ($author->deputy)
                                    <a href="{{ route('deputies.show', $author->deputy->id) }}" 
                                       class="text-sky-600 hover:text-sky-800 hover:underline transition-colors">
                                        {{ $author->name }}
                                    </a>
                                @else
                                    {{ $author->name }}
                                @endif
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Voltar -->
    <div class="mt-6">
        <a href="{{ route('legislative-proposals.index') }}" 
           class="inline-flex items-center text-sky-600 hover:text-sky-800 hover:underline transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Voltar à lista de proposições
        </a>
    </div>
</div>
@endsection