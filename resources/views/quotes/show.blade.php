<x-app-layout>
    @php
        $setting = $quote->user->companySetting;
        $primaryColor = $setting->primary_color ?? '#4f46e5'; // default indigo-600
        $secondaryColor = $setting->secondary_color ?? '#1e3a8a';
        $companyName = $setting->company_name ?? config('app.name', 'Gerador de Orçamentos');
    @endphp

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Visualizar Orçamento') }} - {{ $quote->quote_number }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('quotes.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition ease-in-out duration-150">
                    {{ __('Voltar') }}
                </a>
                <a href="{{ route('quotes.edit', $quote) }}" class="inline-flex items-center px-4 py-2 bg-indigo-50 border border-indigo-200 rounded-md font-semibold text-xs text-indigo-700 uppercase tracking-widest hover:bg-indigo-100 transition ease-in-out duration-150">
                    {{ __('Editar') }}
                </a>
                <!-- E-mail button -->
                <button type="button"
                    id="btn-send-email"
                    onclick="document.getElementById('modal-send-email').classList.remove('hidden')"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 transition ease-in-out duration-150 shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"></path>
                    </svg>
                    {{ __('Enviar por E-mail') }}
                </button>
                <a href="{{ route('quotes.pdf', $quote) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-slate-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700 active:bg-slate-900 transition ease-in-out duration-150 shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"></path>
                    </svg>
                    {{ __('Exportar PDF') }}
                </a>
                <a href="{{ route('quotes.receipt', $quote) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 active:bg-emerald-900 transition ease-in-out duration-150 shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V8.25H8.25z"></path>
                    </svg>
                    {{ __('Gerar Recibo') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-2">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Proposal card sheet style -->
            <div class="bg-white shadow-lg rounded-2xl border border-gray-150 overflow-hidden" style="--primary-color: {{ $primaryColor }}; --secondary-color: {{ $secondaryColor }};">
                <!-- Top strip: Brand style / colors -->
                <div class="h-2" style="background-color: var(--primary-color);"></div>

                <div class="p-8 md:p-12">
                    <!-- Header Section -->
                    <div class="flex flex-col md:flex-row justify-between items-start gap-6 border-b border-gray-150 pb-8 mb-8">
                        <div>
                            @if($setting && $setting->logo_path)
                                <div class="mb-4">
                                    <img src="{{ asset('storage/' . $setting->logo_path) }}" alt="{{ $companyName }}" class="max-h-16 object-contain" />
                                </div>
                            @else
                                <div class="flex items-center gap-2 font-heading font-bold text-2xl tracking-tight mb-2" style="color: var(--primary-color);">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path>
                                    </svg>
                                    <span>{{ $companyName }}</span>
                                </div>
                            @endif
                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">{{ __('Prestador de Serviços') }}</p>
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
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
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

                    <!-- Client and Invoice details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-b border-gray-150 pb-8 mb-8">
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
                            @if($quote->client->address)
                                <p class="text-sm text-gray-600 mt-0.5"><span class="font-medium text-gray-500">Endereço:</span> {{ $quote->client->address }}</p>
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
                        <div class="border border-gray-150 rounded-xl overflow-hidden">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-slate-50 border-b border-gray-150">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 font-semibold">{{ __('Serviço / Descrição') }}</th>
                                        <th scope="col" class="px-6 py-3 text-center w-16 font-semibold">{{ __('Qtd') }}</th>
                                        <th scope="col" class="px-6 py-3 text-right w-32 font-semibold">{{ __('Preço Unitário') }}</th>
                                        <th scope="col" class="px-6 py-3 text-right w-32 font-semibold">{{ __('Total') }}</th>
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
                                        <tr class="hover:bg-slate-50 transition duration-150">
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
                                <div class="bg-slate-50 p-4 rounded-xl border border-gray-150 text-xs leading-relaxed whitespace-pre-line">
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
                        <div class="border-t border-gray-150 pt-8 mt-8 flex flex-col items-center md:items-end">
                            <div class="w-64 text-center">
                                @if($setting->signature_path)
                                    <img src="{{ asset('storage/' . $setting->signature_path) }}" alt="Assinatura" class="max-h-16 mx-auto object-contain mb-2" />
                                @endif
                                <div class="border-t border-gray-300 pt-2 text-xs text-gray-500 font-semibold uppercase tracking-wider leading-normal">
                                    {!! nl2br(e($setting->signature_text ?? __('Assinatura do Prestador'))) !!}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- ===== MODAL: ENVIAR POR E-MAIL ===== --}}
<div id="modal-send-email"
     class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
     x-data
     @keydown.escape.window="$el.classList.add('hidden')">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">Enviar Orçamento por E-mail</h3>
                    <p class="text-xs text-gray-500">{{ $quote->quote_number }} — o PDF será anexado automaticamente</p>
                </div>
            </div>
            <button type="button"
                onclick="document.getElementById('modal-send-email').classList.add('hidden')"
                class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="form-send-email" method="POST" action="{{ route('quotes.sendEmail', $quote) }}">
            @csrf
            <div class="px-6 py-5 space-y-4">

                <!-- Recipient -->
                <div>
                    <label for="recipient_email" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">
                        Destinatário (E-mail do Cliente)
                    </label>
                    <input
                        type="email"
                        id="recipient_email"
                        name="recipient_email"
                        value="{{ $quote->client->email }}"
                        required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition"
                        placeholder="email@cliente.com.br"
                    />
                    <p class="text-xs text-gray-400 mt-1">Pré-preenchido com o e-mail cadastrado do cliente. Edite se necessário.</p>
                </div>

                <!-- Custom Message -->
                <div>
                    <label for="custom_message" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">
                        Mensagem Personalizada <span class="font-normal text-gray-400 normal-case">(opcional)</span>
                    </label>
                    <textarea
                        id="custom_message"
                        name="custom_message"
                        rows="4"
                        maxlength="1000"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition resize-none"
                        placeholder="Ex: Fico à disposição para qualquer dúvida. Aguardo seu retorno."
                    ></textarea>
                    <p class="text-xs text-gray-400 mt-1">Esta mensagem aparecerá em destaque no corpo do e-mail.</p>
                </div>

                <!-- Info box -->
                <div class="flex items-start gap-3 bg-blue-50 border border-blue-100 rounded-lg px-4 py-3">
                    <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"></path>
                    </svg>
                    <p class="text-xs text-blue-700">
                        O PDF da proposta será gerado automaticamente com o template configurado e enviado como anexo.
                        @if($quote->status === 'draft')
                            <strong>O status será atualizado para "Enviado" automaticamente.</strong>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end gap-3 px-6 py-4 bg-gray-50 border-t border-gray-100">
                <button type="button"
                    onclick="document.getElementById('modal-send-email').classList.add('hidden')"
                    class="px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Cancelar
                </button>
                <button type="submit"
                    id="btn-confirm-send"
                    class="inline-flex items-center gap-2 px-5 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 active:bg-blue-800 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"></path>
                    </svg>
                    Enviar E-mail
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Show loading state when sending
document.getElementById('form-send-email')?.addEventListener('submit', function() {
    const btn = document.getElementById('btn-confirm-send');
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> Enviando...';
});

// Close modal clicking outside
document.getElementById('modal-send-email')?.addEventListener('click', function(e) {
    if (e.target === this) this.classList.add('hidden');
});
</script>
