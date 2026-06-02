<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Orçamentos') }}
            </h2>
            <a href="{{ route('quotes.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                </svg>
                {{ __('Novo Orçamento') }}
            </a>
        </div>
    </x-slot>

    <div class="py-2">
        
            <!-- Flash Messages -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-md shadow-sm flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm font-medium text-green-800">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-md shadow-sm flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-red-500 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm font-medium text-red-800">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
                <!-- Stat 1 -->
                <div class="bg-white p-6 rounded-xl border border-gray-150 shadow-sm">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('Total') }}</p>
                    <h4 class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['total'] }}</h4>
                </div>
                <!-- Stat 2 -->
                <div class="bg-white p-6 rounded-xl border border-gray-150 shadow-sm">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('Rascunho') }}</p>
                    <h4 class="text-2xl font-bold text-slate-600 mt-2">{{ $stats['pending'] }}</h4>
                </div>
                <!-- Stat 3 -->
                <div class="bg-white p-6 rounded-xl border border-gray-150 shadow-sm">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('Enviados') }}</p>
                    <h4 class="text-2xl font-bold text-blue-600 mt-2">{{ $stats['sent'] }}</h4>
                </div>
                <!-- Stat 4 -->
                <div class="bg-white p-6 rounded-xl border border-gray-150 shadow-sm">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('Aprovados') }}</p>
                    <h4 class="text-2xl font-bold text-green-600 mt-2">{{ $stats['approved'] }}</h4>
                </div>
                <!-- Stat 5 -->
                <div class="bg-white p-6 rounded-xl border border-gray-150 shadow-sm">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('Aprovado (R$)') }}</p>
                    <h4 class="text-2xl font-bold text-indigo-600 mt-2">R$ {{ number_format($stats['total_value'], 2, ',', '.') }}</h4>
                </div>
            </div>

            {{-- Painel de Filtros --}}
            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-150 mb-4">
                <form method="GET" action="{{ route('quotes.index') }}" id="filter-form">
                    <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
                            <span class="text-sm font-semibold text-gray-700">{{ __('Filtros') }}</span>
                            @if(request()->hasAny(['search','status','client_id','date_from','date_to']))
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                                    {{ __('Ativos') }}
                                </span>
                            @endif
                        </div>
                        @if(request()->hasAny(['search','status','client_id','date_from','date_to']))
                            <a href="{{ route('quotes.index') }}" class="text-xs text-red-500 hover:text-red-700 font-medium transition">
                                ✕ {{ __('Limpar filtros') }}
                            </a>
                        @endif
                    </div>
                    <div class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                        {{-- Busca livre --}}
                        <div class="lg:col-span-2">
                            <label for="search" class="block text-xs font-medium text-gray-600 mb-1">{{ __('Buscar') }}</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/></svg>
                                </span>
                                <input type="text" id="search" name="search" value="{{ request('search') }}"
                                    placeholder="Nº orçamento ou cliente..."
                                    class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none transition">
                            </div>
                        </div>

                        {{-- Status --}}
                        <div>
                            <label for="filter-status" class="block text-xs font-medium text-gray-600 mb-1">{{ __('Status') }}</label>
                            <select id="filter-status" name="status"
                                class="w-full py-2 px-3 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none transition bg-white">
                                <option value="">{{ __('Todos') }}</option>
                                <option value="draft"    {{ request('status') === 'draft'    ? 'selected' : '' }}>{{ __('Rascunho') }}</option>
                                <option value="sent"     {{ request('status') === 'sent'     ? 'selected' : '' }}>{{ __('Enviado') }}</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>{{ __('Aprovado') }}</option>
                                <option value="declined" {{ request('status') === 'declined' ? 'selected' : '' }}>{{ __('Recusado') }}</option>
                            </select>
                        </div>

                        {{-- Cliente --}}
                        <div>
                            <label for="filter-client" class="block text-xs font-medium text-gray-600 mb-1">{{ __('Cliente') }}</label>
                            <select id="filter-client" name="client_id"
                                class="w-full py-2 px-3 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none transition bg-white">
                                <option value="">{{ __('Todos') }}</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Período --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">{{ __('Período') }}</label>
                            <div class="flex gap-1 items-center">
                                <input type="date" name="date_from" value="{{ request('date_from') }}"
                                    title="{{ __('De') }}"
                                    class="w-full py-2 px-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none transition">
                                <span class="text-gray-400 text-xs shrink-0">→</span>
                                <input type="date" name="date_to" value="{{ request('date_to') }}"
                                    title="{{ __('Até') }}"
                                    class="w-full py-2 px-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none transition">
                            </div>
                        </div>
                    </div>

                    <div class="px-4 pb-4 flex gap-2">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-md uppercase tracking-widest transition">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/></svg>
                            {{ __('Filtrar') }}
                        </button>
                    </div>
                </form>
            </div>

            {{-- Resultado dos filtros --}}
            @if(request()->hasAny(['search','status','client_id','date_from','date_to']))
                <p class="text-xs text-gray-500 mb-2 px-1">
                    {{ $quotes->total() }} {{ $quotes->total() === 1 ? 'resultado encontrado' : 'resultados encontrados' }}
                </p>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-150">
                @if ($quotes->isEmpty())
                    <!-- Empty State -->
                    <div class="p-12 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 text-indigo-500 mb-4 border border-indigo-100">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path>
                            </svg>
                        </div>
                        @if(request()->hasAny(['search','status','client_id','date_from','date_to']))
                            <h3 class="text-lg font-bold text-gray-900 mb-1">{{ __('Nenhum resultado encontrado') }}</h3>
                            <p class="text-gray-500 text-sm mb-6 max-w-sm mx-auto">
                                {{ __('Tente ajustar ou limpar os filtros aplicados.') }}
                            </p>
                            <a href="{{ route('quotes.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold text-xs rounded-md uppercase tracking-widest transition duration-150">
                                {{ __('Limpar filtros') }}
                            </a>
                        @else
                            <h3 class="text-lg font-bold text-gray-900 mb-1">{{ __('Nenhum orçamento gerado') }}</h3>
                            <p class="text-gray-500 text-sm mb-6 max-w-sm mx-auto">
                                {{ __('Crie propostas comerciais detalhadas com múltiplos serviços, descontos e exporte tudo em PDF.') }}
                            </p>
                            <a href="{{ route('quotes.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-md uppercase tracking-widest transition duration-150">
                                {{ __('Criar meu primeiro orçamento') }}
                            </a>
                        @endif
                    </div>
                @else
                    <!-- Table List -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-slate-50 border-b border-gray-150">
                                <tr>
                                    <th scope="col" class="px-6 py-4 font-semibold">{{ __('Número') }}</th>
                                    <th scope="col" class="px-6 py-4 font-semibold">{{ __('Cliente') }}</th>
                                    <th scope="col" class="px-6 py-4 font-semibold">{{ __('Emissão') }}</th>
                                    <th scope="col" class="px-6 py-4 font-semibold">{{ __('Status') }}</th>
                                    <th scope="col" class="px-6 py-4 font-semibold">{{ __('Valor Total') }}</th>
                                    <th scope="col" class="px-6 py-4 font-semibold text-right">{{ __('Ações') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($quotes as $quote)
                                    <tr class="bg-white hover:bg-slate-50 transition duration-150">
                                        <td class="px-6 py-4 font-medium text-gray-900">
                                            <a href="{{ route('quotes.show', $quote) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">
                                                {{ $quote->quote_number }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 font-semibold text-gray-900">
                                            {{ $quote->client->name }}
                                        </td>
                                        <td class="px-6 py-4 text-gray-600">
                                            {{ \Carbon\Carbon::parse($quote->issue_date)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($quote->status === 'draft')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                                    {{ __('Rascunho') }}
                                                </span>
                                            @elseif ($quote->status === 'sent')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ __('Enviado') }}
                                                </span>
                                            @elseif ($quote->status === 'approved')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    {{ __('Aprovado') }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    {{ __('Recusado') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 font-semibold text-gray-900">
                                            R$ {{ number_format($quote->total_amount, 2, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-2">
                                            <a href="{{ route('quotes.show', $quote) }}" class="inline-flex items-center px-2.5 py-1.5 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" title="Ver Detalhes">
                                                {{ __('Ver') }}
                                            </a>
                                            <a href="{{ route('quotes.pdf', $quote) }}" target="_blank" class="inline-flex items-center px-2.5 py-1.5 bg-slate-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" title="Visualizar PDF">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"></path>
                                                </svg>
                                                PDF
                                            </a>
                                            <a href="{{ route('quotes.receipt', $quote) }}" target="_blank" class="inline-flex items-center px-2.5 py-1.5 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150" title="Gerar Recibo">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V8.25H8.25z"></path>
                                                </svg>
                                                Recibo
                                            </a>
                                            <a href="{{ route('quotes.edit', $quote) }}" class="inline-flex items-center px-2.5 py-1.5 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                {{ __('Editar') }}
                                            </a>
                                            <a href="{{ route('quotes.show', $quote) }}#send-email"
                                               onclick="event.preventDefault(); window.location='{{ route('quotes.show', $quote) }}'; setTimeout(()=>document.getElementById('btn-send-email')?.click(),300);"
                                               class="inline-flex items-center px-2.5 py-1.5 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150" title="Enviar por E-mail">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"></path>
                                                </svg>
                                                Email
                                            </a>
                                            <form action="{{ route('quotes.duplicate', $quote) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center px-2.5 py-1.5 bg-purple-50 border border-purple-200 rounded-md font-semibold text-xs text-purple-700 uppercase tracking-widest shadow-sm hover:bg-purple-100 hover:border-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150" title="Duplicar Orçamento">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                                                    </svg>
                                                    Duplicar
                                                </button>
                                            </form>
                                            <form action="{{ route('quotes.destroy', $quote) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este orçamento? Todos os itens associados serão apagados.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-2.5 py-1.5 bg-red-50 border border-red-200 rounded-md font-semibold text-xs text-red-700 uppercase tracking-widest shadow-sm hover:bg-red-100 hover:border-red-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                    {{ __('Excluir') }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($quotes->hasPages())
                        <div class="px-6 py-4 bg-slate-50 border-t border-gray-150">
                            {{ $quotes->links() }}
                        </div>
                    @endif
                @endif
            </div>
    </div>
</x-app-layout>
