<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Novo Orçamento') }}
        </h2>
    </x-slot>

    <div class="py-2">
        
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-150 p-8">
                <!-- Errors alert -->
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-md">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">
                                    {{ __('Por favor, corrija os erros abaixo:') }}
                                </h3>
                                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('quotes.store') }}" method="POST" x-data="quoteForm()">
                    @csrf

                    <!-- Grid superior: Cliente, Status, Emissão, Vencimento -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <div>
                            <x-input-label for="client_id" :value="__('Cliente')" />
                            <select id="client_id" name="client_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">-- {{ __('Selecione o Cliente') }} --</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="status" :value="__('Status Inicial')" />
                            <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>{{ __('Rascunho') }}</option>
                                <option value="sent" {{ old('status') == 'sent' ? 'selected' : '' }}>{{ __('Enviado') }}</option>
                                <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>{{ __('Aprovado') }}</option>
                                <option value="declined" {{ old('status') == 'declined' ? 'selected' : '' }}>{{ __('Recusado') }}</option>
                            </select>
                        </div>

                        <div>
                            <x-input-label for="issue_date" :value="__('Data de Emissão')" />
                            <x-text-input id="issue_date" class="block mt-1 w-full" type="date" name="issue_date" value="{{ old('issue_date', now()->format('Y-m-d')) }}" required />
                        </div>

                        <div>
                            <x-input-label for="due_date" :value="__('Data de Vencimento')" />
                            <x-text-input id="due_date" class="block mt-1 w-full" type="date" name="due_date" value="{{ old('due_date') }}" />
                        </div>
                    </div>

                    <!-- Divisor -->
                    <hr class="border-gray-150 my-6">

                    <!-- Seção de Seleção Rápida de Serviços Catalogados -->
                    <div class="mb-6 bg-slate-50 p-4 rounded-xl border border-gray-150">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">{{ __('Adicionar Serviço do Catálogo') }}</h4>
                        <div class="flex flex-col md:flex-row gap-4 items-end">
                            <div class="flex-1">
                                <select x-model="selectedServiceIndex" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                                    <option value="-1">-- {{ __('Escolha um serviço cadastrado...') }} --</option>
                                    <template x-for="(service, index) in catalogServices" :key="service.id">
                                        <option :value="index" x-text="`${service.name} (R$ ${formatNumber(service.default_price)})`"></option>
                                    </template>
                                </select>
                            </div>
                            <button type="button" @click="addCatalogItem()" class="inline-flex items-center px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 rounded-md font-semibold text-xs uppercase tracking-widest transition duration-150">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                                </svg>
                                {{ __('Adicionar Item') }}
                            </button>
                        </div>
                    </div>

                    <!-- Tabela de Itens (Orçamento) -->
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Itens do Orçamento') }}</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500 border border-gray-150 rounded-xl overflow-hidden">
                                <thead class="text-xs text-gray-700 uppercase bg-slate-50 border-b border-gray-150">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 w-1/2 font-semibold">{{ __('Descrição do Serviço/Produto') }}</th>
                                        <th scope="col" class="px-6 py-3 w-16 text-center font-semibold">{{ __('Qtd') }}</th>
                                        <th scope="col" class="px-6 py-3 w-32 font-semibold">{{ __('Preço Unitário') }}</th>
                                        <th scope="col" class="px-6 py-3 w-32 font-semibold">{{ __('Total') }}</th>
                                        <th scope="col" class="px-6 py-3 w-16 text-right font-semibold"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-150 bg-white">
                                    <template x-for="(item, index) in items" :key="index">
                                        <tr class="hover:bg-slate-50 transition duration-150">
                                            <td class="px-6 py-3">
                                                <input type="text" :name="`items[${index}][description]`" x-model="item.description" placeholder="{{ __('Descreva o serviço executado...') }}" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" required />
                                            </td>
                                            <td class="px-6 py-3 text-center">
                                                <input type="number" :name="`items[${index}][quantity]`" x-model.number="item.quantity" min="1" class="w-16 text-center border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" required />
                                            </td>
                                            <td class="px-6 py-3">
                                                <div class="relative rounded-md shadow-sm">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <span class="text-gray-500 text-xs">R$</span>
                                                    </div>
                                                    <input type="text" :name="`items[${index}][unit_price]`" x-model="item.unit_price" placeholder="0,00" class="pl-8 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" required />
                                                </div>
                                            </td>
                                            <td class="px-6 py-3 font-semibold text-gray-900 text-sm">
                                                <span x-text="formatCurrency(calculateRowTotal(item))"></span>
                                            </td>
                                            <td class="px-6 py-3 text-right">
                                                <button type="button" @click="removeItem(index)" class="text-red-500 hover:text-red-700" title="{{ __('Remover Item') }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <!-- Botão de adicionar item customizado avulso -->
                        <div class="mt-4">
                            <button type="button" @click="addCustomItem()" class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                                </svg>
                                {{ __('Adicionar Item Personalizado') }}
                            </button>
                        </div>
                    </div>

                    <!-- Divisor -->
                    <hr class="border-gray-150 my-6">

                    <!-- Grid inferior: Notas e Sumário financeiro -->
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
                        <!-- Notas / Observações -->
                        <div class="md:col-span-8">
                            <x-input-label for="notes" :value="__('Notas / Observações adicionais')" />
                            <textarea id="notes" name="notes" rows="4" placeholder="{{ __('Termos de garantia, forma de pagamento, detalhes de entrega...') }}" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">{{ old('notes') }}</textarea>
                        </div>

                        <!-- Valores Globais -->
                        <div class="md:col-span-4 bg-slate-50 p-6 rounded-2xl border border-gray-150 space-y-4">
                            <div class="flex justify-between items-center text-sm text-gray-600">
                                <span>{{ __('Subtotal') }}</span>
                                <span class="font-semibold text-gray-900" x-text="formatCurrency(calculateSubtotal())"></span>
                            </div>

                            <div>
                                <x-input-label for="discount" :value="__('Desconto (R$)')" />
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 text-xs">R$</span>
                                    </div>
                                    <input type="text" id="discount" name="discount" x-model="discount" class="pl-8 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" placeholder="0,00" />
                                </div>
                            </div>

                            <div class="border-t border-gray-200 pt-4 flex justify-between items-center">
                                <span class="text-base font-bold text-gray-900">{{ __('Valor Total') }}</span>
                                <span class="text-xl font-bold text-indigo-600" x-text="formatCurrency(calculateTotal())"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Botões de Ação -->
                    <div class="mt-8 flex justify-end gap-3 border-t border-gray-150 pt-6">
                        <a href="{{ route('quotes.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Cancelar') }}
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                            {{ __('Salvar Orçamento') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Alpine.js form controller script -->
    <script>
        function quoteForm() {
            return {
                // Initialize items array with one empty item
                items: [
                    { description: '', quantity: 1, unit_price: '' }
                ],
                catalogServices: @json($services),
                selectedServiceIndex: -1,
                discount: '',

                addCustomItem() {
                    this.items.push({ description: '', quantity: 1, unit_price: '' });
                },

                addCatalogItem() {
                    const index = parseInt(this.selectedServiceIndex);
                    if (index >= 0 && index < this.catalogServices.length) {
                        const service = this.catalogServices[index];
                        this.items.push({
                            description: service.name,
                            quantity: 1,
                            unit_price: this.formatNumber(service.default_price)
                        });
                        this.selectedServiceIndex = -1; // Reset dropdown
                    }
                },

                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                    } else {
                        // Reset the only item left
                        this.items[0] = { description: '', quantity: 1, unit_price: '' };
                    }
                },

                parseMoney(val) {
                    if (!val) return 0;
                    // Replace dots (thousands separators) and change comma to dot
                    let clean = val.toString().replace(/\./g, '').replace(',', '.');
                    return parseFloat(clean) || 0;
                },

                calculateRowTotal(item) {
                    const price = this.parseMoney(item.unit_price);
                    const qty = parseInt(item.quantity) || 0;
                    return price * qty;
                },

                calculateSubtotal() {
                    return this.items.reduce((sum, item) => sum + this.calculateRowTotal(item), 0);
                },

                calculateTotal() {
                    const subtotal = this.calculateSubtotal();
                    const disc = this.parseMoney(this.discount);
                    return Math.max(0, subtotal - disc);
                },

                formatNumber(num) {
                    // Standard BRL helper for inputs (e.g. 1500.50 -> 1.500,50)
                    return new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(num);
                },

                formatCurrency(val) {
                    return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(val);
                }
            };
        }
    </script>
</x-app-layout>
