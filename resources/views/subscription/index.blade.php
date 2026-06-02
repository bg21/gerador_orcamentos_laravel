<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight">
            {{ __('Planos e Assinaturas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('error'))
                <div class="mb-8 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl flex items-center gap-3">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <p class="font-medium text-sm">{{ session('error') }}</p>
                </div>
            @endif

            @if (auth()->user()->isPro())
                <div class="bg-gradient-to-r from-emerald-500 to-teal-500 rounded-2xl shadow-xl p-8 md:p-12 mb-12 text-white flex flex-col md:flex-row items-center justify-between gap-8">
                    <div>
                        <h3 class="text-2xl md:text-3xl font-bold mb-2">Você é um cliente Pro! ⭐</h3>
                        <p class="text-emerald-50 max-w-xl text-lg">Obrigado por assinar o nosso plano Premium. Você tem acesso a todas as funcionalidades ilimitadas, envio de e-mail e personalização avançada.</p>
                    </div>
                    <div>
                        <a href="{{ route('subscription.portal') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-bold rounded-xl text-emerald-700 bg-white hover:bg-emerald-50 transition shadow-sm w-full md:w-auto">
                            Gerenciar Assinatura
                        </a>
                    </div>
                </div>
            @else
                <div class="text-center mb-12">
                    <h3 class="text-3xl font-extrabold text-slate-900 mb-4 tracking-tight">Evolua suas vendas com o Plano Pro</h3>
                    <p class="text-slate-500 text-lg max-w-2xl mx-auto">Remova os limites do plano gratuito, envie orçamentos com a sua marca direto para o WhatsApp ou E-mail do cliente e aumente sua taxa de fechamento.</p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                
                {{-- Plano Gratuito --}}
                <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm flex flex-col relative opacity-80 hover:opacity-100 transition">
                    <div class="mb-6">
                        <h4 class="text-2xl font-bold text-slate-900 mb-2">Gratuito</h4>
                        <p class="text-slate-500 text-sm">Ideal para quem está começando e tem baixo volume de orçamentos.</p>
                    </div>
                    
                    <div class="mb-8">
                        <span class="text-4xl font-extrabold text-slate-900">R$ 0</span>
                        <span class="text-slate-500 font-medium">/mês</span>
                    </div>

                    <ul class="space-y-4 mb-8 flex-1">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span class="text-slate-600 text-sm">Até <strong class="text-slate-900">3 orçamentos</strong> mensais</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span class="text-slate-600 text-sm">Até <strong class="text-slate-900">5 clientes</strong></span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span class="text-slate-600 text-sm">Template de PDF Padrão</span>
                        </li>
                        <li class="flex items-start gap-3 opacity-50">
                            <svg class="w-5 h-5 text-slate-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            <span class="text-slate-500 text-sm line-through">Envio direto por e-mail</span>
                        </li>
                        <li class="flex items-start gap-3 opacity-50">
                            <svg class="w-5 h-5 text-slate-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            <span class="text-slate-500 text-sm line-through">Logotipo e Cores Personalizadas</span>
                        </li>
                        <li class="flex items-start gap-3 opacity-50">
                            <svg class="w-5 h-5 text-slate-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            <span class="text-slate-500 text-sm line-through">Todos os templates Premium</span>
                        </li>
                    </ul>

                    @if (!auth()->user()->isPro())
                        <div class="py-3 px-4 bg-slate-100 text-slate-600 font-bold text-center rounded-xl text-sm border border-slate-200">
                            Seu plano atual
                        </div>
                    @endif
                </div>

                {{-- Plano PRO --}}
                <div class="rounded-3xl p-8 border shadow-2xl flex flex-col relative transform md:-translate-y-4" style="background: linear-gradient(to bottom, #1e1b4b, #0f172a); border-color: #4f46e5;">
                    <div class="absolute -top-4 left-0 right-0 flex justify-center">
                        <span class="bg-gradient-to-r from-indigo-500 to-purple-500 text-white text-xs font-black uppercase tracking-widest py-1.5 px-4 rounded-full shadow-lg">Mais Popular</span>
                    </div>

                    <div class="mb-6 mt-4">
                        <h4 class="text-2xl font-bold text-white mb-2">Profissional</h4>
                        <p class="text-indigo-200 text-sm">Acesso total e ilimitado para profissionais que levam seu negócio a sério.</p>
                    </div>
                    
                    <div class="mb-8">
                        <span class="text-4xl font-extrabold text-white">R$ 29,90</span>
                        <span class="text-indigo-300 font-medium">/mês</span>
                    </div>

                    <ul class="space-y-4 mb-8 flex-1">
                        <li class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-indigo-500/20 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-indigo-50 text-sm"><strong class="text-white">Orçamentos Ilimitados</strong></span>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-indigo-500/20 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-indigo-50 text-sm"><strong class="text-white">Clientes Ilimitados</strong></span>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-indigo-500/20 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-indigo-50 text-sm">Envio de Orçamentos por <strong class="text-white">E-mail em 1 clique</strong></span>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-indigo-500/20 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-indigo-50 text-sm"><strong class="text-white">Logotipo e Cores</strong> Personalizadas no PDF</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-indigo-500/20 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-indigo-50 text-sm">Acesso a <strong class="text-white">Todos os Templates Premium</strong></span>
                        </li>
                    </ul>

                    @if (!auth()->user()->isPro())
                        <form action="{{ route('subscription.checkout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-4 px-4 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-center rounded-xl text-sm transition shadow-lg shadow-indigo-600/30">
                                Assinar Plano Pro
                            </button>
                        </form>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
