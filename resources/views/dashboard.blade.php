<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight">{{ __('Dashboard') }}</h2>
    </x-slot>

    {{-- Chart.js via CDN --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    @endpush

    <div class="py-2 space-y-8">

        {{-- Welcome --}}
        <div class="bg-gradient-to-r from-slate-900 to-slate-800 text-white rounded-2xl p-6 md:p-8 shadow-lg border border-slate-700/50">
            <h3 class="text-xl md:text-2xl font-bold font-heading mb-2">Bem-vindo ao seu Painel de Vendas!</h3>
            <p class="text-slate-300 text-sm md:text-base max-w-xl leading-relaxed">
                Acompanhe o faturamento gerado pelas suas propostas, gerencie clientes e controle orçamentos em tempo real.
            </p>
        </div>

        {{-- KPI Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <div class="bg-white rounded-2xl p-6 border border-gray-150 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
                <div class="space-y-1">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Faturamento Aprovado</p>
                    <h4 class="text-xl md:text-2xl font-extrabold text-green-600 tracking-tight">R$ {{ number_format($approvedAmount, 2, ',', '.') }}</h4>
                    <p class="text-xs text-gray-500">Orçamentos aprovados</p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-gray-150 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
                <div class="space-y-1">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Faturamento Pendente</p>
                    <h4 class="text-xl md:text-2xl font-extrabold text-blue-600 tracking-tight">R$ {{ number_format($pendingAmount, 2, ',', '.') }}</h4>
                    <p class="text-xs text-gray-500">Orçamentos enviados</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-gray-150 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
                <div class="space-y-1">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Clientes Ativos</p>
                    <h4 class="text-xl md:text-2xl font-extrabold text-gray-900 tracking-tight">{{ $totalClients }}</h4>
                    <p class="text-xs text-gray-500">Clientes cadastrados</p>
                </div>
                <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-gray-150 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
                <div class="space-y-1">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Taxa de Conversão</p>
                    <h4 class="text-xl md:text-2xl font-extrabold tracking-tight {{ $conversionRate >= 50 ? 'text-green-600' : ($conversionRate > 0 ? 'text-amber-500' : 'text-gray-400') }}">
                        {{ $conversionRate }}%
                    </h4>
                    <p class="text-xs text-gray-500">Aprovados / enviados</p>
                </div>
                <div class="w-12 h-12 bg-violet-50 rounded-xl flex items-center justify-center text-violet-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/></svg>
                </div>
            </div>
        </div>

        {{-- Charts Row --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Bar Chart: Receita Mensal --}}
            <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-150 shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h4 class="font-bold text-gray-900 text-lg">Receita Aprovada</h4>
                        <p class="text-xs text-gray-400 mt-0.5">Últimos 12 meses — orçamentos aprovados</p>
                    </div>
                    <div class="w-8 h-8 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                    </div>
                </div>
                <div class="relative h-64">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            {{-- Donut Chart: Status --}}
            <div class="bg-white rounded-2xl border border-gray-150 shadow-sm p-6 flex flex-col">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h4 class="font-bold text-gray-900 text-lg">Status dos Orçamentos</h4>
                        <p class="text-xs text-gray-400 mt-0.5">Distribuição por situação</p>
                    </div>
                    <div class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z"/><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z"/></svg>
                    </div>
                </div>

                @if($totalQuotes > 0)
                    <div class="relative h-48 mx-auto w-48">
                        <canvas id="statusChart"></canvas>
                    </div>
                    {{-- Legend --}}
                    <div class="mt-4 space-y-2">
                        @php
                            $legendMap = [
                                'Rascunho' => ['#94a3b8', 'bg-slate-400'],
                                'Enviado'  => ['#3b82f6', 'bg-blue-500'],
                                'Aprovado' => ['#22c55e', 'bg-green-500'],
                                'Recusado' => ['#ef4444', 'bg-red-500'],
                            ];
                        @endphp
                        @foreach(array_combine($donutLabels, $donutData) as $label => $count)
                            <div class="flex items-center justify-between text-sm">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full {{ $legendMap[$label][1] ?? 'bg-gray-400' }}"></span>
                                    <span class="text-gray-600 font-medium">{{ $label }}</span>
                                </div>
                                <span class="font-bold text-gray-900">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex-1 flex flex-col items-center justify-center text-center text-gray-400 py-8">
                        <svg class="w-12 h-12 mb-3 opacity-30" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z"/></svg>
                        <p class="text-sm">Nenhum orçamento ainda</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Bottom Row --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Recent Quotes --}}
            <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-150 overflow-hidden shadow-sm">
                <div class="px-6 py-5 border-b border-gray-150 flex items-center justify-between">
                    <h4 class="font-bold text-gray-900 text-lg">Orçamentos Recentes</h4>
                    <a href="{{ route('quotes.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors uppercase tracking-wider">Ver Todos &rarr;</a>
                </div>

                @if($recentQuotes->isEmpty())
                    <div class="p-8 text-center text-gray-400 text-sm">Nenhum orçamento gerado ainda. Comece criando um novo!</div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-slate-50 border-b border-gray-150">
                                <tr>
                                    <th class="px-6 py-3 font-semibold">Número</th>
                                    <th class="px-6 py-3 font-semibold">Cliente</th>
                                    <th class="px-6 py-3 font-semibold text-right">Valor</th>
                                    <th class="px-6 py-3 font-semibold text-center">Status</th>
                                    <th class="px-6 py-3 font-semibold text-center">Ação</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @foreach($recentQuotes as $quote)
                                    <tr class="hover:bg-slate-50 transition duration-150">
                                        <td class="px-6 py-4 font-bold text-gray-900">{{ $quote->quote_number }}</td>
                                        <td class="px-6 py-4 font-medium text-gray-800">{{ $quote->client->name }}</td>
                                        <td class="px-6 py-4 text-right font-bold text-gray-900">R$ {{ number_format($quote->total_amount, 2, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-center">
                                            @php
                                                $badges = [
                                                    'draft'    => 'bg-slate-100 text-slate-800',
                                                    'sent'     => 'bg-blue-100 text-blue-800',
                                                    'approved' => 'bg-green-100 text-green-800',
                                                    'declined' => 'bg-red-100 text-red-800',
                                                ];
                                                $labels = ['draft'=>'Rascunho','sent'=>'Enviado','approved'=>'Aprovado','declined'=>'Recusado'];
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badges[$quote->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ $labels[$quote->status] ?? $quote->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('quotes.show', $quote) }}" class="inline-flex items-center px-3 py-1 bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 rounded-md font-bold text-xs text-gray-700 transition duration-150">Ver</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- Right Side --}}
            <div class="space-y-6">
                {{-- Quick Actions --}}
                <div class="bg-white rounded-2xl border border-gray-150 p-6 shadow-sm">
                    <h4 class="font-bold text-gray-900 text-lg mb-4">Ações Rápidas</h4>
                    <div class="flex flex-col gap-3">
                        <a href="{{ route('quotes.create') }}" class="flex items-center justify-between p-3.5 bg-indigo-50/50 hover:bg-indigo-50 border border-indigo-100/50 rounded-xl font-bold text-sm text-indigo-700 transition group">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                <span>Criar Novo Orçamento</span>
                            </span>
                            <span class="opacity-0 group-hover:opacity-100 transition-opacity">&rarr;</span>
                        </a>
                        <a href="{{ route('clients.create') }}" class="flex items-center justify-between p-3.5 bg-slate-50 hover:bg-slate-100 border border-gray-200/60 rounded-xl font-semibold text-sm text-gray-700 transition group">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"/></svg>
                                <span>Adicionar Cliente</span>
                            </span>
                            <span class="opacity-0 group-hover:opacity-100 transition-opacity">&rarr;</span>
                        </a>
                        <a href="{{ route('settings.edit') }}" class="flex items-center justify-between p-3.5 bg-slate-50 hover:bg-slate-100 border border-gray-200/60 rounded-xl font-semibold text-sm text-gray-700 transition group">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 010-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>Configurações</span>
                            </span>
                            <span class="opacity-0 group-hover:opacity-100 transition-opacity">&rarr;</span>
                        </a>
                    </div>
                </div>

                {{-- Tip --}}
                <div class="bg-indigo-50/50 border border-indigo-100/50 rounded-2xl p-6">
                    <div class="flex items-start gap-3">
                        <span class="text-xl">💡</span>
                        <div class="space-y-1">
                            <h5 class="font-bold text-indigo-900 text-sm">Dica Comercial</h5>
                            <p class="text-xs text-indigo-700 leading-relaxed">
                                Propostas com logotipo e assinatura profissional têm taxa de conversão até 45% maior. Acesse <a href="{{ route('settings.edit') }}" class="font-bold underline">Configurações</a> para completar sua identidade visual.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart.js Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script>
        // ── Receita Mensal (Bar Chart) ────────────────────────────────────────
        const revenueCtx = document.getElementById('revenueChart');
        if (revenueCtx) {
            new Chart(revenueCtx, {
                type: 'bar',
                data: {
                    labels: @json($months),
                    datasets: [{
                        label: 'Receita Aprovada (R$)',
                        data: @json($revenueData),
                        backgroundColor: 'rgba(79, 70, 229, 0.15)',
                        borderColor: 'rgba(79, 70, 229, 0.9)',
                        borderWidth: 2,
                        borderRadius: 6,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: ctx => ' R$ ' + ctx.parsed.y.toLocaleString('pt-BR', {minimumFractionDigits: 2})
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.04)' },
                            ticks: {
                                font: { size: 11 },
                                callback: v => 'R$ ' + (v >= 1000 ? (v/1000).toFixed(0)+'k' : v)
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11 } }
                        }
                    }
                }
            });
        }

        // ── Distribuição de Status (Donut) ────────────────────────────────────
        const statusCtx = document.getElementById('statusChart');
        if (statusCtx) {
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($donutLabels),
                    datasets: [{
                        data: @json($donutData),
                        backgroundColor: @json($donutColors),
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: ctx => ' ' + ctx.label + ': ' + ctx.parsed + ' orçamento(s)'
                            }
                        }
                    }
                }
            });
        }
    </script>

</x-app-layout>
