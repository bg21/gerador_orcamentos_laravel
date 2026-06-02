<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Orçamento {{ $quote->quote_number }} — {{ $quote->user->companySetting?->company_name ?? $quote->user->name }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts / Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Outfit', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
    </style>
</head>
<body class="antialiased min-h-screen text-slate-700 bg-slate-50 flex flex-col" x-data="{ showApproveModal: false, showDeclineModal: false }">

    @php
        $setting = $quote->user->companySetting;
        $primaryColor = $setting->primary_color ?? '#4f46e5'; // default indigo-600
        $secondaryColor = $setting->secondary_color ?? '#1e3a8a';
        $companyName = $setting->company_name ?? $quote->user->name;
    @endphp

    <!-- Public Access Top Action Bar -->
    <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-gray-150 py-4 px-6 md:px-8">
        <div class="max-w-5xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
            
            <!-- Branding / Status -->
            <div class="flex items-center gap-3">
                <span class="text-lg font-bold tracking-tight text-gray-900">
                    {{ $companyName }}
                </span>
                <span class="text-xs text-gray-300">|</span>
                <span class="text-sm text-gray-500 font-medium">
                    Proposta {{ $quote->quote_number }}
                </span>
            </div>

            <!-- Client Action Buttons -->
            <div class="flex items-center gap-2.5">
                <!-- Download PDF -->
                <a href="{{ route('public.quote.pdf', $quote->share_token) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-xl font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"></path>
                    </svg>
                    PDF
                </a>

                @if ($quote->status === 'sent' || $quote->status === 'draft')
                    <!-- Recusar -->
                    <button type="button" @click="showDeclineModal = true" class="inline-flex items-center px-4 py-2 bg-red-50 border border-red-200 rounded-xl font-semibold text-xs text-red-700 uppercase tracking-widest hover:bg-red-100 transition">
                        Recusar
                    </button>

                    <!-- Aprovar -->
                    <button type="button" @click="showApproveModal = true" class="inline-flex items-center px-5 py-2 bg-emerald-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 active:bg-emerald-900 transition shadow-md">
                        Aprovar Proposta
                    </button>
                @endif
            </div>

        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow py-8 px-4 md:px-8">
        <div class="max-w-4xl mx-auto">
            
            <!-- Status Alert Banner -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl shadow-sm flex items-center gap-3">
                    <svg class="w-6 h-6 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="font-bold text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session('info'))
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 text-blue-800 rounded-2xl shadow-sm flex items-center gap-3">
                    <svg class="w-6 h-6 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="font-medium text-sm">{{ session('info') }}</p>
                </div>
            @endif

            @if ($quote->status === 'approved')
                <div class="mb-8 p-6 bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 rounded-3xl shadow-sm text-center flex flex-col items-center gap-3">
                    <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 animate-bounce">
                        🎉
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-emerald-900">Esta proposta já está aprovada!</h3>
                        <p class="text-sm text-emerald-700 mt-1">Aprovação registrada em {{ $quote->updated_at->format('d/m/Y \à\s H:i') }}. Obrigado pela parceria!</p>
                    </div>
                </div>
            @elseif ($quote->status === 'declined')
                <div class="mb-8 p-6 bg-red-50 border border-red-200 rounded-3xl shadow-sm text-center flex flex-col items-center gap-3">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center text-red-600">
                        ✕
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-red-900">Esta proposta foi recusada.</h3>
                        <p class="text-sm text-red-700 mt-1">Nós registramos o seu feedback e estamos prontos para realizar os ajustes necessários.</p>
                    </div>
                </div>
            @endif

            <!-- Invoice Sheet -->
            <div class="bg-white shadow-xl rounded-3xl border border-gray-150 overflow-hidden" style="--primary-color: {{ $primaryColor }}; --secondary-color: {{ $secondaryColor }};">
                <div class="h-2.5" style="background-color: var(--primary-color);"></div>

                <div class="p-8 md:p-12">
                    
                    <!-- Top section: Logo and Quote identification -->
                    <div class="flex flex-col md:flex-row justify-between items-start gap-6 border-b border-gray-100 pb-8 mb-8">
                        <div>
                            @if($setting && $setting->logo_path)
                                <div class="mb-4">
                                    <img src="{{ asset('storage/' . $setting->logo_path) }}" alt="{{ $companyName }}" class="max-h-16 object-contain" />
                                </div>
                            @else
                                <div class="flex items-center gap-2 font-bold text-2xl tracking-tight mb-2" style="color: var(--primary-color);">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path>
                                    </svg>
                                    <span>{{ $companyName }}</span>
                                </div>
                            @endif
                            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider">{{ __('Prestador de Serviços') }}</p>
                            <h3 class="text-base font-bold text-gray-800 mt-1">{{ $setting->company_name ?? $quote->user->name }}</h3>
                            @if($setting && $setting->document)
                                <p class="text-sm text-gray-500 mt-0.5"><span class="font-medium text-gray-400">CNPJ/CPF:</span> {{ $setting->document }}</p>
                            @endif
                            <p class="text-sm text-gray-500 mt-0.5"><span class="font-medium text-gray-400">E-mail:</span> {{ $setting->email ?? $quote->user->email }}</p>
                            @if($setting && $setting->phone)
                                <p class="text-sm text-gray-500 mt-0.5"><span class="font-medium text-gray-400">Telefone:</span> {{ $setting->phone }}</p>
                            @endif
                            @if($setting && $setting->address)
                                <p class="text-sm text-gray-500 mt-0.5"><span class="font-medium text-gray-400">Endereço:</span> {{ $setting->address }}</p>
                            @endif
                        </div>
                        <div class="text-left md:text-right">
                            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ $quote->quote_number }}</h2>
                            <div class="mt-2">
                                @if ($quote->status === 'draft')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-800">
                                        {{ __('Rascunho') }}
                                    </span>
                                @elseif ($quote->status === 'sent')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                        {{ __('Enviado') }}
                                    </span>
                                @elseif ($quote->status === 'approved')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                        {{ __('Aprovado') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        {{ __('Recusado') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Client and Dates details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-b border-gray-100 pb-8 mb-8">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">{{ __('Orçado Para:') }}</p>
                            <h4 class="text-lg font-bold text-gray-900">{{ $quote->client->name }}</h4>
                            @if($quote->client->document)
                                <p class="text-sm text-gray-600 mt-1"><span class="font-medium text-gray-500">Documento:</span> {{ $quote->client->document }}</p>
                            @endif
                            <p class="text-sm text-gray-600 mt-0.5"><span class="font-medium text-gray-500">E-mail:</span> {{ $quote->client->email }}</p>
                            @if($quote->client->phone)
                                <p class="text-sm text-gray-600 mt-0.5"><span class="font-medium text-gray-500">Telefone:</span> {{ $quote->client->phone }}</p>
                            @endif
                        </div>
                        <div class="flex flex-col justify-start md:items-end gap-3 text-left md:text-right">
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('Data de Emissão:') }}</p>
                                <p class="text-sm font-semibold text-gray-800 mt-1">{{ \Carbon\Carbon::parse($quote->issue_date)->format('d/m/Y') }}</p>
                            </div>
                            @if($quote->due_date)
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('Data de Vencimento:') }}</p>
                                    <p class="text-sm font-semibold text-gray-800 mt-1">{{ \Carbon\Carbon::parse($quote->due_date)->format('d/m/Y') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="mb-8">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">{{ __('Especificação dos Serviços:') }}</p>
                        <div class="border border-gray-200 rounded-2xl overflow-hidden">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-slate-50 border-b border-gray-150">
                                    <tr>
                                        <th scope="col" class="px-6 py-3.5 font-semibold text-gray-600">{{ __('Serviço / Descrição') }}</th>
                                        <th scope="col" class="px-6 py-3.5 text-center w-16 font-semibold text-gray-600">{{ __('Qtd') }}</th>
                                        <th scope="col" class="px-6 py-3.5 text-right w-32 font-semibold text-gray-600">{{ __('Preço Unitário') }}</th>
                                        <th scope="col" class="px-6 py-3.5 text-right w-32 font-semibold text-gray-600">{{ __('Total') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @php
                                        $subtotal = 0;
                                    @endphp
                                    @foreach ($quote->items as $item)
                                        @php
                                            $subtotal += $item->total_price;
                                        @endphp
                                        <tr class="hover:bg-slate-50/55 transition">
                                            <td class="px-6 py-4 font-semibold text-gray-800">
                                                {{ $item->description }}
                                            </td>
                                            <td class="px-6 py-4 text-center text-gray-600 font-medium">
                                                {{ $item->quantity }}
                                            </td>
                                            <td class="px-6 py-4 text-right text-gray-800">
                                                R$ {{ number_format($item->unit_price, 2, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 text-right font-bold text-gray-900">
                                                R$ {{ number_format($item->total_price, 2, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Financial Summary & Notes -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                        <!-- Notes -->
                        <div class="text-gray-600">
                            @if($quote->notes)
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">{{ __('Termos & Observações:') }}</p>
                                <div class="bg-slate-50 p-4 rounded-xl border border-gray-150 text-xs leading-relaxed whitespace-pre-line text-slate-600">
                                    {{ $quote->notes }}
                                </div>
                            @endif
                        </div>

                        <!-- Summary calculations -->
                        <div class="bg-slate-50 p-6 rounded-2xl border border-gray-150 space-y-3.5 text-sm">
                            <div class="flex justify-between items-center text-gray-600">
                                <span>{{ __('Subtotal') }}</span>
                                <span class="font-bold text-gray-800">R$ {{ number_format($subtotal, 2, ',', '.') }}</span>
                            </div>

                            @if($quote->discount > 0)
                                <div class="flex justify-between items-center text-green-600 font-medium">
                                    <span>{{ __('Desconto (-)') }}</span>
                                    <span>R$ {{ number_format($quote->discount, 2, ',', '.') }}</span>
                                </div>
                            @endif

                            <div class="border-t border-gray-200 pt-3.5 flex justify-between items-center font-bold text-gray-900">
                                <span class="text-base">{{ __('Valor Total') }}</span>
                                <span class="text-xl font-bold" style="color: var(--primary-color);">R$ {{ number_format($quote->total_amount, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Signature Section -->
                    @if($setting && ($setting->signature_path || $setting->signature_text))
                        <div class="border-t border-gray-100 pt-8 mt-8 flex flex-col items-center md:items-end">
                            <div class="w-64 text-center">
                                @if($setting->signature_path)
                                    <img src="{{ asset('storage/' . $setting->signature_path) }}" alt="Assinatura" class="max-h-16 mx-auto object-contain mb-2" />
                                @endif
                                <div class="border-t border-gray-200 pt-2 text-xs text-gray-400 font-semibold uppercase tracking-wider leading-normal">
                                    {!! nl2br(e($setting->signature_text ?? __('Assinatura do Prestador'))) !!}
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-100 py-8 border-t border-gray-200 text-center text-xs text-gray-400">
        <p>Gerado de forma segura e compartilhada por {{ $companyName }} usando o {{ config('app.name') }}.</p>
    </footer>

    {{-- ===== MODAL: APROVAR ===== --}}
    <div id="modal-approve" 
         x-show="showApproveModal"
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
         @keydown.escape.window="showApproveModal = false">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md mx-4 overflow-hidden border border-gray-100" @click.away="showApproveModal = false">
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-2xl mx-auto mb-4 animate-pulse">
                    ✓
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Aprovar Proposta Comercial?</h3>
                <p class="text-sm text-gray-500 mb-6">
                    Ao aprovar, o prestador de serviços será notificado por e-mail e iniciará os preparativos da prestação de serviço.
                </p>

                <form method="POST" action="{{ route('public.quote.approve', $quote->share_token) }}">
                    @csrf
                    <div class="flex justify-center gap-3">
                        <button type="button" @click="showApproveModal = false" class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition w-1/2">
                            Cancelar
                        </button>
                        <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-emerald-600 rounded-xl hover:bg-emerald-700 transition w-1/2 shadow-md">
                            Sim, Aprovar!
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== MODAL: RECUSAR ===== --}}
    <div id="modal-decline" 
         x-show="showDeclineModal"
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
         @keydown.escape.window="showDeclineModal = false">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md mx-4 overflow-hidden border border-gray-100" @click.away="showDeclineModal = false">
            <div class="p-6">
                <div class="w-12 h-12 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-xl mb-4">
                    ✕
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-1">Recusar Proposta?</h3>
                <p class="text-sm text-gray-500 mb-4">
                    Gostaria de informar o motivo da recusa? Isso nos ajuda a readequar os termos e valores às suas necessidades.
                </p>

                <form method="POST" action="{{ route('public.quote.decline', $quote->share_token) }}">
                    @csrf
                    
                    <div class="mb-5">
                        <label for="reason" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Motivo / Ajustes necessários (opcional)</label>
                        <textarea 
                            id="reason" 
                            name="reason" 
                            rows="3" 
                            class="w-full rounded-xl border border-gray-300 px-4 py-2 text-sm text-gray-900 focus:border-red-500 focus:ring-red-200 focus:outline-none transition resize-none"
                            placeholder="Ex: O orçamento está fora do planejado, ou gostaríamos de reduzir a quantidade de horas..."></textarea>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" @click="showDeclineModal = false" class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition w-1/2 text-center">
                            Voltar
                        </button>
                        <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-red-600 rounded-xl hover:bg-red-700 transition w-1/2 shadow-md">
                            Confirmar Recusa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
