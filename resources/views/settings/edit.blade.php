<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Configurações da Empresa') }}
        </h2>
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

            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Card 1: Dados Gerais -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-150 p-6 md:p-8">
                    <div class="border-b border-gray-150 pb-4 mb-6">
                        <h3 class="text-lg font-bold text-gray-900">{{ __('Dados Cadastrais da Empresa') }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ __('Essas informações aparecerão no cabeçalho das suas propostas comerciais.') }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="company_name" :value="__('Nome da Empresa / Fantasia')" />
                            <x-text-input id="company_name" name="company_name" type="text" class="mt-1 block w-full" value="{{ old('company_name', $setting->company_name) }}" />
                        </div>

                        <div>
                            <x-input-label for="document" :value="__('CPF ou CNPJ')" />
                            <x-text-input id="document" name="document" type="text" class="mt-1 block w-full" value="{{ old('document', $setting->document) }}" placeholder="00.000.000/0001-00" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('E-mail de Contato')" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" value="{{ old('email', $setting->email) }}" />
                        </div>

                        <div>
                            <x-input-label for="phone" :value="__('Telefone / WhatsApp')" />
                            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" value="{{ old('phone', $setting->phone) }}" placeholder="(11) 99999-9999" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="address" :value="__('Endereço Comercial')" />
                            <textarea id="address" name="address" rows="2" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" placeholder="Rua, Número, Bairro, Cidade - UF">{{ old('address', $setting->address) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Identidade Visual -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-150 p-6 md:p-8">
                    <div class="border-b border-gray-150 pb-4 mb-6">
                        <h3 class="text-lg font-bold text-gray-900">{{ __('Identidade Visual & Cores') }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ __('Defina o logotipo e a paleta de cores para customizar a apresentação do PDF.') }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
                        <!-- Logo Upload -->
                        <div class="md:col-span-8 space-y-4">
                            <div>
                                <x-input-label for="logo" :value="__('Logotipo da Empresa')" />
                                <input type="file" id="logo" name="logo" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer" />
                                <p class="text-xs text-gray-400 mt-1.5">{{ __('Formatos recomendados: PNG ou SVG com fundo transparente. Máx. 2MB.') }}</p>
                            </div>

                            @if ($setting->logo_path)
                                <div class="p-4 bg-slate-50 border border-gray-200 rounded-xl inline-block">
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">{{ __('Logotipo Atual:') }}</p>
                                    <img src="{{ asset('storage/' . $setting->logo_path) }}" alt="Logo preview" class="max-h-16 object-contain" />
                                </div>
                            @endif
                        </div>

                        <!-- Color Pickers -->
                        <div class="md:col-span-4 bg-slate-50 p-6 rounded-2xl border border-gray-150 space-y-4">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('Crescer da Marca no PDF') }}</h4>
                            
                            <div class="flex items-center gap-3">
                                <input type="color" id="primary_color" name="primary_color" value="{{ old('primary_color', $setting->primary_color) }}" class="w-12 h-10 border border-gray-300 rounded-md cursor-pointer shadow-sm p-0" />
                                <div>
                                    <x-input-label for="primary_color" :value="__('Cor Primária')" />
                                    <span class="text-xs text-gray-500" x-text="primary_color">{{ old('primary_color', $setting->primary_color) }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <input type="color" id="secondary_color" name="secondary_color" value="{{ old('secondary_color', $setting->secondary_color) }}" class="w-12 h-10 border border-gray-300 rounded-md cursor-pointer shadow-sm p-0" />
                                <div>
                                    <x-input-label for="secondary_color" :value="__('Cor Secundária')" />
                                    <span class="text-xs text-gray-500">{{ old('secondary_color', $setting->secondary_color) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Assinatura -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-150 p-6 md:p-8">
                    <div class="border-b border-gray-150 pb-4 mb-6">
                        <h3 class="text-lg font-bold text-gray-900">{{ __('Assinatura do Prestador') }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ __('Você pode anexar uma assinatura escaneada/digitalizada e definir o texto de credenciais.') }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
                        <div class="space-y-4">
                            <div>
                                <x-input-label for="signature" :value="__('Imagem da Assinatura')" />
                                <input type="file" id="signature" name="signature" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer" />
                                <p class="text-xs text-gray-400 mt-1.5">{{ __('Formatos recomendados: PNG transparente contendo apenas o traço. Máx. 2MB.') }}</p>
                            </div>

                            @if ($setting->signature_path)
                                <div class="p-4 bg-slate-50 border border-gray-200 rounded-xl inline-block">
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">{{ __('Assinatura Atual:') }}</p>
                                    <img src="{{ asset('storage/' . $setting->signature_path) }}" alt="Signature preview" class="max-h-16 object-contain" />
                                </div>
                            @endif
                        </div>

                        <div>
                            <x-input-label for="signature_text" :value="__('Texto de Credenciais / Cargo')" />
                            <textarea id="signature_text" name="signature_text" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" placeholder="Nome Completo&#10;Engenheiro de Software - CREA 12345&#10;Sócio Fundador">{{ old('signature_text', $setting->signature_text) }}</textarea>
                            <p class="text-xs text-gray-400 mt-1.5">{{ __('Este texto será exibido logo abaixo da imagem da assinatura no orçamento.') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Card 4: E-mail Padrão -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-150 p-6 md:p-8">
                    <div class="border-b border-gray-150 pb-4 mb-6">
                        <h3 class="text-lg font-bold text-gray-900">{{ __('Mensagem Padrão de E-mail') }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ __('Configure o assunto e a mensagem padrão que aparecerão ao enviar um orçamento por e-mail para o cliente.') }}</p>
                    </div>

                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <x-input-label for="default_email_subject" :value="__('Assunto Padrão do E-mail')" />
                            <x-text-input id="default_email_subject" name="default_email_subject" type="text" class="mt-1 block w-full" value="{{ old('default_email_subject', $setting->default_email_subject) }}" placeholder="Ex: Proposta Comercial - {Sua Empresa}" />
                            <p class="text-xs text-gray-400 mt-1.5">{{ __('Assunto sugerido ao abrir a janela de envio.') }}</p>
                        </div>

                        <div>
                            <x-input-label for="default_email_message" :value="__('Corpo do E-mail Padrão')" />
                            <textarea id="default_email_message" name="default_email_message" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" placeholder="Olá!&#10;Segue em anexo a proposta comercial.&#10;Fico à disposição para dúvidas.">{{ old('default_email_message', $setting->default_email_message) }}</textarea>
                            <p class="text-xs text-gray-400 mt-1.5">{{ __('Mensagem pré-preenchida no envio de orçamento. Você poderá alterá-la antes de cada envio, se necessário.') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Card 5: Modelo de PDF -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-150 p-6 md:p-8">
                    <div class="border-b border-gray-150 pb-4 mb-6">
                        <h3 class="text-lg font-bold text-gray-900">{{ __('Modelo de Orçamento em PDF') }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ __('Escolha o estilo visual que será utilizado ao exportar seus orçamentos.') }}</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4" x-data="{ selectedTemplate: '{{ old('pdf_template', $setting->pdf_template ?? 'classic') }}' }">
                        @php
                            $templates = [
                                'classic'  => ['label' => 'Clássico',  'desc' => 'Layout profissional com seções organizadas, linhas divisórias e caixa de resumo lateral.',   'icon' => '🗂️'],
                                'modern'   => ['label' => 'Moderno',   'desc' => 'Minimalista e limpo com duas colunas paralelas, numeração de itens e total à direita.', 'icon' => '✨'],
                                'tabular'  => ['label' => 'Tabelado',  'desc' => 'Estrutura totalmente delimitada por bordas, com seção de cliente em grid e metadados em linha.', 'icon' => '📋'],
                                'premium'  => ['label' => 'Premium',   'desc' => 'Cabeçalho corporativo estilizado com banner de total destacado na cor da marca.',   'icon' => '🏆'],
                            ];
                            $currentTemplate = old('pdf_template', $setting->pdf_template ?? 'classic');
                        @endphp

                        @foreach($templates as $key => $info)
                        <div class="relative flex flex-col h-full">
                            <input type="radio"
                                   id="tpl_{{ $key }}"
                                   name="pdf_template"
                                   value="{{ $key }}"
                                   class="sr-only"
                                   x-model="selectedTemplate">
                            
                            <label for="tpl_{{ $key }}"
                                   class="relative cursor-pointer rounded-xl border-2 p-4 flex flex-col gap-2 transition-all duration-150 h-full"
                                   :class="selectedTemplate === '{{ $key }}' ? 'border-indigo-600 bg-indigo-50 shadow-md' : 'border-gray-200 hover:border-indigo-400 hover:shadow-sm'">

                                <!-- Top: icon + check -->
                                <div class="flex items-center justify-between">
                                    <span class="text-2xl">{{ $info['icon'] }}</span>
                                    <span class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-colors"
                                          :class="selectedTemplate === '{{ $key }}' ? 'border-indigo-600 bg-indigo-600' : 'border-gray-300'">
                                        <svg x-show="selectedTemplate === '{{ $key }}'" style="display: none;" class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    </span>
                                </div>

                                <!-- Name -->
                                <div class="font-bold text-sm text-gray-900">{{ $info['label'] }}</div>

                                <!-- Description -->
                                <div class="text-xs text-gray-500 leading-relaxed flex-grow">{{ $info['desc'] }}</div>
                                
                                <!-- Preview Link -->
                                <a href="{{ route('settings.template.preview', ['template' => $key]) }}" target="_blank" class="mt-3 inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-xs font-medium text-gray-700 hover:bg-gray-50 hover:text-indigo-600 transition-colors shadow-sm w-full">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Ver Preview
                                </a>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Card 5: Modelo de Recibo -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-150 p-6 md:p-8 mt-6">
                    <div class="border-b border-gray-150 pb-4 mb-6">
                        <h3 class="text-lg font-bold text-gray-900">{{ __('Modelo de Recibo em PDF') }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ __('Escolha o estilo visual que será utilizado ao emitir os recibos de pagamento.') }}</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" x-data="{ selectedReceiptTemplate: '{{ old('receipt_template', $setting->receipt_template ?? 'modern') }}' }">
                        @php
                            $receiptTemplates = [
                                'classic'  => ['label' => 'Declaração',  'desc' => 'Recibo tradicional em formato de texto corrido (declaração).', 'icon' => '📜'],
                                'tabular'  => ['label' => 'Tabelado',  'desc' => 'Estrutura com grade de bordas simples para campos Recebedor e Pagador.', 'icon' => '📋'],
                                'modern'   => ['label' => 'Moderno',   'desc' => 'Layout limpo com grid aberto e tipografia elegante e espaçada.', 'icon' => '✨'],
                            ];
                        @endphp

                        @foreach($receiptTemplates as $key => $info)
                        <div class="relative flex flex-col h-full">
                            <input type="radio"
                                   id="receipt_tpl_{{ $key }}"
                                   name="receipt_template"
                                   value="{{ $key }}"
                                   class="sr-only"
                                   x-model="selectedReceiptTemplate">
                            
                            <label for="receipt_tpl_{{ $key }}"
                                   class="relative cursor-pointer rounded-xl border-2 p-4 flex flex-col gap-2 transition-all duration-150 h-full"
                                   :class="selectedReceiptTemplate === '{{ $key }}' ? 'border-emerald-600 bg-emerald-50 shadow-md' : 'border-gray-200 hover:border-emerald-400 hover:shadow-sm'">

                                <!-- Top: icon + check -->
                                <div class="flex items-center justify-between">
                                    <span class="text-2xl">{{ $info['icon'] }}</span>
                                    <span class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-colors"
                                          :class="selectedReceiptTemplate === '{{ $key }}' ? 'border-emerald-600 bg-emerald-600' : 'border-gray-300'">
                                        <svg x-show="selectedReceiptTemplate === '{{ $key }}'" style="display: none;" class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    </span>
                                </div>

                                <!-- Name -->
                                <div class="font-bold text-sm text-gray-900">{{ $info['label'] }}</div>

                                <!-- Description -->
                                <div class="text-xs text-gray-500 leading-relaxed flex-grow">{{ $info['desc'] }}</div>
                                
                                <!-- Preview Link -->
                                <a href="{{ route('settings.receipt.preview', ['template' => $key]) }}" target="_blank" class="mt-3 inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-xs font-medium text-gray-700 hover:bg-gray-50 hover:text-emerald-600 transition-colors shadow-sm w-full">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Ver Preview
                                </a>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Botões -->
                <div class="flex justify-end gap-3 pt-4">
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                        {{ __('Salvar Configurações') }}
                    </button>
                </div>
            </form>
    </div>
</x-app-layout>
