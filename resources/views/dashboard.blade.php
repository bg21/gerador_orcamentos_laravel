<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-2">
        <!-- Welcome Card -->
        <div class="bg-gradient-to-r from-slate-900 to-slate-800 text-white rounded-2xl p-6 md:p-8 shadow-lg mb-8 border border-slate-700/50">
            <h3 class="text-xl md:text-2xl font-bold font-heading mb-2">Bem-vindo ao seu Painel de Vendas!</h3>
            <p class="text-slate-300 text-sm md:text-base max-w-xl leading-relaxed">
                Aqui você acompanha o faturamento gerado pelas suas propostas comerciais, gerencia seus clientes e controla seus orçamentos de forma ágil e centralizada.
            </p>
        </div>

        <!-- KPI Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Card 1: Faturamento Aprovado -->
            <div class="bg-white rounded-2xl p-6 border border-gray-150 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
                <div class="space-y-1">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('Faturamento Aprovado') }}</p>
                    <h4 class="text-xl md:text-2xl font-extrabold text-green-600 tracking-tight">
                        R$ {{ number_format($approvedAmount, 2, ',', '.') }}
                    </h4>
                    <p class="text-xs text-gray-500 font-medium">Orçamentos aprovados</p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0110 20.307a3.745 3.745 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.748 3.748 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0114 3.693a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.748 3.748 0 0121 12z"></path>
                    </svg>
                </div>
            </div>

            <!-- Card 2: Faturamento Pendente -->
            <div class="bg-white rounded-2xl p-6 border border-gray-150 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
                <div class="space-y-1">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('Faturamento Pendente') }}</p>
                    <h4 class="text-xl md:text-2xl font-extrabold text-blue-600 tracking-tight">
                        R$ {{ number_format($pendingAmount, 2, ',', '.') }}
                    </h4>
                    <p class="text-xs text-gray-500 font-medium">Orçamentos enviados</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"></path>
                    </svg>
                </div>
            </div>

            <!-- Card 3: Total de Clientes -->
            <div class="bg-white rounded-2xl p-6 border border-gray-150 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
                <div class="space-y-1">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('Clientes Ativos') }}</p>
                    <h4 class="text-xl md:text-2xl font-extrabold text-gray-900 tracking-tight">
                        {{ $totalClients }}
                    </h4>
                    <p class="text-xs text-gray-500 font-medium">Clientes cadastrados</p>
                </div>
                <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.386 11.386 0 0110.089 18H8.25c-1.357 0-2.612-.4-3.663-1.09l-.004-.003C3.562 16.2 3 14.885 3 13.5A4.5 4.5 0 017.5 9h1.386A9.39 9.39 0 0115 11.693M20.25 10.5c.34 0 .668.046.98.132C20.894 8.203 18.232 6.5 15 6.5c-.482 0-.952.037-1.409.108m2.158 5.892a3.75 3.75 0 10-7.5 0 3.75 3.75 0 007.5 0zM15 6.5a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Card 4: Total de Orçamentos -->
            <div class="bg-white rounded-2xl p-6 border border-gray-150 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
                <div class="space-y-1">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('Orçamentos Criados') }}</p>
                    <h4 class="text-xl md:text-2xl font-extrabold text-gray-900 tracking-tight">
                        {{ $totalQuotes }}
                    </h4>
                    <p class="text-xs text-gray-500 font-medium">Propostas geradas</p>
                </div>
                <div class="w-12 h-12 bg-violet-50 rounded-xl flex items-center justify-center text-violet-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Lower Section Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Side: Recent Quotes -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-150 overflow-hidden shadow-sm flex flex-col justify-between">
                <div>
                    <!-- Section Header -->
                    <div class="px-6 py-5 border-b border-gray-150 flex items-center justify-between">
                        <h4 class="font-bold text-gray-900 text-lg">{{ __('Orçamentos Recentes') }}</h4>
                        <a href="{{ route('quotes.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors uppercase tracking-wider">{{ __('Ver Todos') }} &rarr;</a>
                    </div>

                    <!-- Table -->
                    @if($recentQuotes->isEmpty())
                        <div class="p-8 text-center text-gray-400 text-sm">
                            Nenhum orçamento gerado ainda. Comece criando um novo!
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-slate-50 border-b border-gray-150">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 font-semibold">{{ __('Número') }}</th>
                                        <th scope="col" class="px-6 py-3 font-semibold">{{ __('Cliente') }}</th>
                                        <th scope="col" class="px-6 py-3 font-semibold text-right">{{ __('Valor Total') }}</th>
                                        <th scope="col" class="px-6 py-3 font-semibold text-center">{{ __('Status') }}</th>
                                        <th scope="col" class="px-6 py-3 font-semibold text-center">{{ __('Ação') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @foreach($recentQuotes as $quote)
                                        <tr class="hover:bg-slate-50 transition duration-150">
                                            <td class="px-6 py-4 font-bold text-gray-900">
                                                {{ $quote->quote_number }}
                                            </td>
                                            <td class="px-6 py-4 font-medium text-gray-800">
                                                {{ $quote->client->name }}
                                            </td>
                                            <td class="px-6 py-4 text-right font-bold text-gray-900">
                                                R$ {{ number_format($quote->total_amount, 2, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                @if ($quote->status === 'draft')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-800">
                                                        {{ __('Rascunho') }}
                                                    </span>
                                                @elseif ($quote->status === 'sent')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                        {{ __('Enviado') }}
                                                    </span>
                                                @elseif ($quote->status === 'approved')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                        {{ __('Aprovado') }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                        {{ __('Recusado') }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <a href="{{ route('quotes.show', $quote) }}" class="inline-flex items-center px-3 py-1 bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 rounded-md font-bold text-xs text-gray-700 transition duration-150">
                                                    {{ __('Ver') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Side: Quick Actions & Business Tip -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-2xl border border-gray-150 p-6 shadow-sm">
                    <h4 class="font-bold text-gray-900 text-lg mb-4">{{ __('Ações Rápidas') }}</h4>
                    <div class="flex flex-col gap-3">
                        <a href="{{ route('quotes.create') }}" class="flex items-center justify-between p-3.5 bg-indigo-50/50 hover:bg-indigo-50 border border-indigo-100/50 rounded-xl font-bold text-sm text-indigo-700 transition duration-150 group">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                                </svg>
                                <span>Criar Novo Orçamento</span>
                            </span>
                            <span class="opacity-0 group-hover:opacity-100 transition-opacity">&rarr;</span>
                        </a>

                        <a href="{{ route('clients.create') }}" class="flex items-center justify-between p-3.5 bg-slate-50 hover:bg-slate-100 border border-gray-200/60 rounded-xl font-semibold text-sm text-gray-700 transition duration-150 group">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"></path>
                                </svg>
                                <span>Adicionar Novo Cliente</span>
                            </span>
                            <span class="opacity-0 group-hover:opacity-100 transition-opacity">&rarr;</span>
                        </a>

                        <a href="{{ route('services.create') }}" class="flex items-center justify-between p-3.5 bg-slate-50 hover:bg-slate-100 border border-gray-200/60 rounded-xl font-semibold text-sm text-gray-700 transition duration-150 group">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766l.002-.001a2.25 2.25 0 012.247 2.977l-.004.013a2.252 2.252 0 01-1.246 1.44l-2.496 3.03m-2.207-3.663a9.753 9.753 0 00-1.5 1.5m1.5-1.5a9.753 9.753 0 011.5-1.5"></path>
                                </svg>
                                <span>Cadastrar Serviço</span>
                            </span>
                            <span class="opacity-0 group-hover:opacity-100 transition-opacity">&rarr;</span>
                        </a>
                    </div>
                </div>

                <!-- Business Tip -->
                <div class="bg-indigo-50/50 border border-indigo-100/50 rounded-2xl p-6">
                    <div class="flex items-start gap-3">
                        <span class="text-xl">💡</span>
                        <div class="space-y-1">
                            <h5 class="font-bold text-indigo-900 text-sm">Dica Comercial</h5>
                            <p class="text-xs text-indigo-700 leading-relaxed">
                                Propostas enviadas com logotipo da empresa e assinatura profissional possuem taxa de conversão até 45% maior. Acesse o menu de Configurações para completar sua identidade visual.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
