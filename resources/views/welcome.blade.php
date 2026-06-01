<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerador de Orçamentos - Aumente suas Vendas</title>
    
    <!-- Google Fonts - Inter -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white font-body text-text-dark antialiased">

    <!-- Cookies Banner -->
    <div id="cookies-banner" class="fixed bottom-4 left-4 right-4 md:left-auto md:right-4 md:max-w-md z-50 bg-white border border-slate-200 rounded-2xl p-4 shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4 transition-all duration-300 transform translate-y-0 opacity-100">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-full bg-blue-50 text-cta flex items-center justify-center shrink-0 mt-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3Z"></path>
                </svg>
            </div>
            <p class="text-xs text-slate-600 leading-normal">
                Utilizamos cookies para melhorar sua experiência. Ao continuar navegando, você concorda com nossa <a href="#" class="text-cta hover:underline font-medium">Política de Privacidade</a> e <a href="#" class="text-cta hover:underline font-medium">Termos de Uso</a>.
            </p>
        </div>
        <div class="flex items-center gap-2 w-full md:w-auto justify-end">
            <button onclick="acceptCookies()" class="bg-cta hover:bg-cta-dark text-white text-xs font-semibold px-4 py-2 rounded-xl transition-all duration-200 cursor-pointer">
                Aceitar
            </button>
            <button onclick="acceptCookies()" class="text-slate-500 hover:text-slate-800 text-xs font-medium px-3 py-2 rounded-xl transition-all duration-200 cursor-pointer">
                Personalizar
            </button>
        </div>
    </div>

    <!-- Header / Navbar -->
    <header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <!-- Logo -->
            <a href="#" class="flex items-center gap-2 font-heading font-bold text-xl tracking-tight text-text-dark">
                <svg class="w-6 h-6 text-cta" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"></path>
                </svg>
                <span class="flex items-center">Gerador de <span class="text-cta ml-1">Orçamentos</span></span>
            </a>
            
            <!-- Menu Desktop -->
            <div class="hidden lg:flex items-center gap-8 text-sm font-medium text-slate-600">
                <a href="#recursos" class="hover:text-cta transition-colors duration-150 cursor-pointer">Recursos</a>
                <a href="#como-funciona" class="hover:text-cta transition-colors duration-150 cursor-pointer">Como Funciona</a>
                <a href="#planos" class="hover:text-cta transition-colors duration-150 cursor-pointer">Planos</a>
                <a href="#depoimentos" class="hover:text-cta transition-colors duration-150 cursor-pointer">Depoimentos</a>
                <a href="#" class="hover:text-cta transition-colors duration-150 cursor-pointer">FAQ</a>
                <a href="#" class="hover:text-cta transition-colors duration-150 cursor-pointer">Contato</a>
            </div>

            <!-- Botões de Acesso -->
            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-cta hover:bg-cta-dark text-white font-semibold text-sm px-5 py-2.5 rounded-xl shadow-sm shadow-cta/20 hover:shadow-cta/30 hover:-translate-y-0.5 transition-all duration-200 cursor-pointer">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-cta transition-colors duration-150">Login</a>
                    <a href="{{ route('register') }}" class="bg-cta hover:bg-cta-dark text-white font-semibold text-sm px-5 py-2.5 rounded-xl shadow-sm shadow-cta/20 hover:shadow-cta/30 hover:-translate-y-0.5 transition-all duration-200 cursor-pointer">
                        Começar Grátis
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Hero Section (Dark Blue & Purple Gradient Fundo) -->
    <section class="relative bg-gradient-to-br from-[#0F172A] via-[#1E1B4B] to-[#1E3A8A] pt-20 pb-24 md:py-32 overflow-hidden text-white">
        <!-- Luz de fundo decorativa -->
        <div class="absolute top-[20%] right-[-10%] w-[500px] h-[500px] bg-cta/15 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[400px] h-[400px] bg-indigo-500/10 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <!-- Coluna Texto -->
                <div class="lg:col-span-7 text-left">
                    <!-- Título -->
                    <h1 class="font-heading font-bold text-4xl md:text-5xl lg:text-6xl leading-[1.1] tracking-tight mb-6">
                        Aumente suas vendas em 3x com orçamentos que convertem
                    </h1>
                    <!-- Descrição -->
                    <p class="text-slate-300 text-base md:text-lg max-w-xl mb-8 leading-relaxed">
                        Pare de perder vendas por orçamentos mal apresentados. Crie propostas irresistíveis em 5 minutos e aumente sua conversão em 40% já na primeira semana.
                    </p>
                    
                    <!-- Bullet -->
                    <div class="flex items-center gap-2 mb-8">
                        <div class="w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"></path>
                            </svg>
                        </div>
                        <span class="text-sm md:text-base font-medium text-slate-200">5 minutos para criar orçamentos profissionais</span>
                    </div>

                    <!-- CTAs -->
                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="w-full sm:w-auto text-center bg-cta hover:bg-cta-dark text-white font-semibold text-base px-8 py-4 rounded-xl shadow-lg shadow-cta/25 hover:-translate-y-0.5 transition-all duration-200 cursor-pointer">
                                Acessar Painel
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="w-full sm:w-auto text-center bg-cta hover:bg-cta-dark text-white font-semibold text-base px-8 py-4 rounded-xl shadow-lg shadow-cta/25 hover:-translate-y-0.5 transition-all duration-200 cursor-pointer">
                                Começar Grátis
                            </a>
                        @endauth
                        <a href="#como-funciona" class="w-full sm:w-auto text-center bg-white/10 hover:bg-white/15 text-white border border-white/10 font-semibold text-base px-8 py-4 rounded-xl hover:-translate-y-0.5 transition-all duration-200 cursor-pointer">
                            Ver Demonstração
                        </a>
                    </div>
                </div>

                <!-- Coluna Mockup Visual (Documento de Orçamento Elevado) -->
                <div class="lg:col-span-5 relative">
                    <div class="relative w-full max-w-md mx-auto bg-white rounded-2xl shadow-2xl p-6 text-slate-800 border border-slate-100 overflow-hidden transform lg:rotate-2 hover:rotate-0 transition-transform duration-500">
                        <!-- Cabeçalho do mockup -->
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-5">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-lg bg-cta/10 text-cta flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"></path>
                                    </svg>
                                </div>
                                <span class="font-heading font-bold text-xs tracking-tight text-slate-900">PROPOSTA DE SERVIÇO</span>
                            </div>
                            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100">#ORC-2026</span>
                        </div>

                        <!-- Blocos do Orçamento -->
                        <div class="space-y-4">
                            <!-- Bloco Cliente -->
                            <div>
                                <div class="h-2 w-16 bg-slate-200 rounded mb-2"></div>
                                <div class="h-3 w-40 bg-slate-100 rounded"></div>
                            </div>
                            
                            <!-- Items da Tabela (Falso) -->
                            <div class="border-t border-b border-slate-100 py-3 my-2 space-y-2.5">
                                <div class="flex items-center justify-between">
                                    <div class="h-3 w-32 bg-cta/15 rounded"></div>
                                    <div class="h-3 w-16 bg-slate-100 rounded"></div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="h-3 w-44 bg-slate-100 rounded"></div>
                                    <div class="h-3 w-12 bg-slate-100 rounded"></div>
                                </div>
                            </div>

                            <!-- Total -->
                            <div class="flex items-center justify-between pt-1">
                                <div class="h-4 w-12 bg-slate-300 rounded"></div>
                                <div class="h-5 w-24 bg-cta rounded"></div>
                            </div>
                        </div>

                        <!-- Assinatura / Aceitar proposta -->
                        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                            <div class="h-3 w-28 bg-slate-100 rounded"></div>
                            <div class="w-20 h-7 rounded bg-cta shadow-sm shadow-cta/10"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Recursos Section (Fundo Claro) -->
    <section id="recursos" class="py-24 bg-secondary">
        <div class="max-w-7xl mx-auto px-6">
            <!-- Título central -->
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="font-heading font-bold text-3xl md:text-4xl text-text-dark mb-4 tracking-tight">
                    Como 2.500+ profissionais aumentaram suas vendas
                </h2>
                <p class="text-slate-500 font-body text-base">
                    Veja os resultados reais de quem já transformou seu processo de vendas e aumentou o faturamento
                </p>
            </div>

            <!-- Grid de Cards de Recursos -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="bg-white border border-slate-100 shadow-md hover:shadow-lg hover:border-slate-200/80 rounded-3xl p-8 text-center transition-all duration-300 flex flex-col items-center">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-tr from-cta to-indigo-500 text-white flex items-center justify-center mb-6 shadow-md shadow-cta/10">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"></path>
                        </svg>
                    </div>
                    <h3 class="font-heading font-bold text-xl text-text-dark mb-3">Editor Intuitivo</h3>
                    <p class="text-slate-500 font-body text-sm leading-relaxed">
                        4x mais rápido que Word ou Excel. Nosso editor arraste-e-solte elimina o retrabalho e permite criar orçamentos profissionais em minutos.
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="bg-white border border-slate-100 shadow-md hover:shadow-lg hover:border-slate-200/80 rounded-3xl p-8 text-center transition-all duration-300 flex flex-col items-center">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-tr from-cta to-indigo-500 text-white flex items-center justify-center mb-6 shadow-md shadow-cta/10">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28 -2.28 5.941"></path>
                        </svg>
                    </div>
                    <h3 class="font-heading font-bold text-xl text-text-dark mb-3">Acompanhamento em Tempo Real</h3>
                    <p class="text-slate-500 font-body text-sm leading-relaxed">
                        Nunca perca uma venda por falta de follow-up. Saiba quando seu cliente abriu, visualizou ou aprovou seu orçamento em tempo real.
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="bg-white border border-slate-100 shadow-md hover:shadow-lg hover:border-slate-200/80 rounded-3xl p-8 text-center transition-all duration-300 flex flex-col items-center">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-tr from-cta to-indigo-500 text-white flex items-center justify-center mb-6 shadow-md shadow-cta/10">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 0 0-5.78 1.128 2.25 2.25 0 0 0 2.4 2.245h1.14m10.158-10.742c-.2.8-.73 1.493-1.464 1.897L13 12.042m0 0-.256-.128H9.744L9.5 12.042m3.5 0v3.418c0 .878-.52 1.666-1.316 2.002a2.25 2.25 0 0 1-2.184-.282M18.75 6.75h.008v.008H18.75V6.75Zm-13.5 0h.008v.008H5.25V6.75Z"></path>
                        </svg>
                    </div>
                    <h3 class="font-heading font-bold text-xl text-text-dark mb-3">Templates Profissionais</h3>
                    <p class="text-slate-500 font-body text-sm leading-relaxed">
                        15+ templates criados por designers especialistas em conversão. Personalize com suas cores e logo para manter sua identidade visual.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Como Funciona Section -->
    <section id="como-funciona" class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-6">
            <!-- Título -->
            <div class="text-center max-w-2xl mx-auto mb-20">
                <h2 class="font-heading font-bold text-3xl md:text-4xl text-text-dark mb-4 tracking-tight">
                    De cliente interessado a contrato assinado em 3 passos
                </h2>
            </div>

            <!-- Conteúdo em Duas Colunas -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <!-- Coluna Passos (Esquerda) -->
                <div class="lg:col-span-5 space-y-4">
                    <!-- Passo 1 -->
                    <div class="p-6 rounded-2xl bg-secondary border border-slate-100 hover:border-cta/25 hover:bg-white hover:shadow-md transition-all duration-300 flex items-start gap-4">
                        <div class="w-8 h-8 rounded-full bg-cta text-white flex items-center justify-center font-heading font-bold text-sm shrink-0">
                            1
                        </div>
                        <div>
                            <h4 class="font-heading font-bold text-base text-text-dark mb-1">Escolha o template</h4>
                            <p class="text-xs text-slate-500 font-body leading-relaxed">
                                Selecione entre nossos templates otimizados para conversão de vendas.
                            </p>
                        </div>
                    </div>

                    <!-- Passo 2 -->
                    <div class="p-6 rounded-2xl bg-secondary border border-slate-100 hover:border-cta/25 hover:bg-white hover:shadow-md transition-all duration-300 flex items-start gap-4">
                        <div class="w-8 h-8 rounded-full bg-cta text-white flex items-center justify-center font-heading font-bold text-sm shrink-0">
                            2
                        </div>
                        <div>
                            <h4 class="font-heading font-bold text-base text-text-dark mb-1">Adicione seus serviços</h4>
                            <p class="text-xs text-slate-500 font-body leading-relaxed">
                                Inclua produtos, valores e descrições de forma rápida e intuitiva.
                            </p>
                        </div>
                    </div>

                    <!-- Passo 3 -->
                    <div class="p-6 rounded-2xl bg-secondary border border-slate-100 hover:border-cta/25 hover:bg-white hover:shadow-md transition-all duration-300 flex items-start gap-4">
                        <div class="w-8 h-8 rounded-full bg-cta text-white flex items-center justify-center font-heading font-bold text-sm shrink-0">
                            3
                        </div>
                        <div>
                            <h4 class="font-heading font-bold text-base text-text-dark mb-1">Envie e acompanhe</h4>
                            <p class="text-xs text-slate-500 font-body leading-relaxed">
                                Compartilhe via WhatsApp e veja quando o documento foi visualizado.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Coluna Vídeo (Direita) -->
                <div class="lg:col-span-7 relative">
                    <div class="aspect-video w-full rounded-3xl border border-slate-100 bg-secondary/80 flex flex-col items-center justify-center p-8 text-center relative overflow-hidden group cursor-pointer hover:border-slate-200 transition-all duration-300 shadow-md">
                        <!-- Play Button -->
                        <div class="w-16 h-16 rounded-full bg-cta hover:bg-cta-dark text-white flex items-center justify-center shadow-lg shadow-cta/20 transform group-hover:scale-110 transition-all duration-300 z-10">
                            <svg class="w-8 h-8 fill-current ml-1" viewBox="0 0 20 20">
                                <path d="M4.018 14L14.41 10 4.018 6z"></path>
                            </svg>
                        </div>
                        <!-- Texto -->
                        <div class="mt-4 z-10">
                            <h4 class="font-heading font-bold text-sm text-text-dark">Demonstração em vídeo</h4>
                            <p class="text-xs text-slate-400 font-body mt-1">2 minutos - Veja como é fácil</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Planos de Preço Section -->
    <section id="planos" class="py-24 bg-secondary">
        <div class="max-w-7xl mx-auto px-6">
            <!-- Título -->
            <div class="text-center max-w-2xl mx-auto mb-12">
                <h2 class="font-heading font-bold text-3xl md:text-4xl text-text-dark mb-4 tracking-tight">
                    Planos para cada necessidade
                </h2>
                <p class="text-slate-500 font-body text-base">
                    Escolha o plano ideal para o seu negócio, sem compromissos de longo prazo
                </p>
            </div>

            <!-- Toggle Mensal/Anual -->
            <div class="flex items-center justify-center gap-4 mb-16">
                <div class="inline-flex items-center bg-white border border-slate-200 rounded-full p-1.5 shadow-sm">
                    <button class="bg-cta text-white font-semibold text-xs px-5 py-2.5 rounded-full transition-all cursor-pointer">
                        Mensal
                    </button>
                    <button class="text-slate-500 hover:text-slate-800 font-medium text-xs px-5 py-2.5 rounded-full transition-all cursor-pointer">
                        Anual (20% de desconto)
                    </button>
                </div>
            </div>

            <!-- Grid de Planos -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-stretch">
                <!-- Plano Gratuito -->
                <div class="bg-white border border-slate-100 shadow-md rounded-3xl p-6 flex flex-col justify-between hover:shadow-lg hover:border-slate-200 transition-all duration-300">
                    <div>
                        <h4 class="font-heading font-bold text-lg text-text-dark mb-1">Gratuito</h4>
                        <div class="flex items-baseline gap-1 my-6 text-text-dark">
                            <span class="text-xs font-semibold">R$</span>
                            <span class="text-4xl font-bold font-heading">0</span>
                            <span class="text-xs text-slate-400 font-body">/mês</span>
                        </div>
                        <ul class="space-y-3 text-xs text-slate-500 font-body border-t border-slate-100 pt-6">
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-cta shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"></path></svg>
                                Até 5 orçamentos/mês
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-cta shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"></path></svg>
                                Editor básico de propostas
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-cta shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"></path></svg>
                                Envio básico de PDF
                            </li>
                        </ul>
                    </div>
                    <a href="{{ auth()->check() ? route('dashboard') : route('register') }}" class="mt-8 block text-center w-full bg-secondary hover:bg-slate-100 text-slate-700 font-semibold text-xs py-3.5 rounded-xl transition-all duration-200 border border-slate-200">
                        Começar Agora
                    </a>
                </div>

                <!-- Plano Starter -->
                <div class="bg-white border border-slate-100 shadow-md rounded-3xl p-6 flex flex-col justify-between hover:shadow-lg hover:border-slate-200 transition-all duration-300">
                    <div>
                        <h4 class="font-heading font-bold text-lg text-text-dark mb-1">Starter</h4>
                        <div class="flex items-baseline gap-1 my-6 text-text-dark">
                            <span class="text-xs font-semibold">R$</span>
                            <span class="text-4xl font-bold font-heading">49</span>
                            <span class="text-xs text-slate-400 font-body">/mês</span>
                        </div>
                        <ul class="space-y-3 text-xs text-slate-500 font-body border-t border-slate-100 pt-6">
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-cta shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"></path></svg>
                                Até 30 orçamentos/mês
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-cta shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"></path></svg>
                                Controle básico de status
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-cta shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"></path></svg>
                                Suporte via ticket
                            </li>
                        </ul>
                    </div>
                    <a href="{{ auth()->check() ? route('dashboard') : route('register') }}" class="mt-8 block text-center w-full bg-secondary hover:bg-slate-100 text-slate-700 font-semibold text-xs py-3.5 rounded-xl transition-all duration-200 border border-slate-200">
                        Começar Agora
                    </a>
                </div>

                <!-- Plano Professional (Destaque) -->
                <div class="bg-white border-2 border-cta shadow-xl rounded-3xl p-6 flex flex-col justify-between relative transform scale-102 hover:shadow-2xl transition-all duration-300">
                    <!-- Selo -->
                    <span class="absolute top-0 right-1/2 translate-x-1/2 -translate-y-1/2 bg-cta text-white text-[10px] font-bold px-3 py-1.5 rounded-full uppercase tracking-wider">
                        Mais Popular
                    </span>
                    <div>
                        <h4 class="font-heading font-bold text-lg text-text-dark mb-1">Professional</h4>
                        <div class="flex items-baseline gap-1 my-6 text-text-dark">
                            <span class="text-xs font-semibold">R$</span>
                            <span class="text-4xl font-bold font-heading text-cta">99</span>
                            <span class="text-xs text-slate-400 font-body">/mês</span>
                        </div>
                        <ul class="space-y-3 text-xs text-slate-500 font-body border-t border-slate-100 pt-6">
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-cta shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"></path></svg>
                                <span class="font-semibold text-slate-700">Orçamentos Ilimitados</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-cta shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"></path></svg>
                                Histórico e Versões ativo
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-cta shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"></path></svg>
                                Templates avançados de PDF
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-cta shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"></path></svg>
                                Suporte prioritário via WhatsApp
                            </li>
                        </ul>
                    </div>
                    <a href="{{ auth()->check() ? route('dashboard') : route('register') }}" class="mt-8 block text-center w-full bg-cta hover:bg-cta-dark text-white font-semibold text-xs py-3.5 rounded-xl shadow-md shadow-cta/20 hover:shadow-cta/30 transition-all duration-200">
                        Começar Agora
                    </a>
                </div>

                <!-- Plano Enterprise -->
                <div class="bg-white border border-slate-100 shadow-md rounded-3xl p-6 flex flex-col justify-between hover:shadow-lg hover:border-slate-200 transition-all duration-300">
                    <div>
                        <h4 class="font-heading font-bold text-lg text-text-dark mb-1">Enterprise</h4>
                        <div class="flex items-baseline gap-1 my-6 text-text-dark">
                            <span class="text-xs font-semibold">R$</span>
                            <span class="text-4xl font-bold font-heading">249</span>
                            <span class="text-xs text-slate-400 font-body">/mês</span>
                        </div>
                        <ul class="space-y-3 text-xs text-slate-500 font-body border-t border-slate-100 pt-6">
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-cta shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"></path></svg>
                                Tudo no Professional
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-cta shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"></path></svg>
                                Multi-tenancy / Várias empresas
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-cta shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"></path></svg>
                                Painel de Gerente administrativo
                            </li>
                        </ul>
                    </div>
                    <a href="{{ auth()->check() ? route('dashboard') : route('register') }}" class="mt-8 block text-center w-full bg-secondary hover:bg-slate-100 text-slate-700 font-semibold text-xs py-3.5 rounded-xl transition-all duration-200 border border-slate-200">
                        Começar Agora
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Depoimentos Section (Prova Social) -->
    <section id="depoimentos" class="py-24 bg-white border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-6">
            <!-- Título -->
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="font-heading font-bold text-3xl md:text-4xl text-text-dark mb-4 tracking-tight">O que dizem nossos usuários</h2>
                <p class="text-slate-500 font-body text-base">Profissionais e agências que aumentaram sua velocidade de propostas e a taxa de fechamento.</p>
            </div>

            <!-- Grid de Depoimentos -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Depoimento 1 -->
                <div class="bg-secondary border border-slate-100 rounded-3xl p-8 flex flex-col justify-between shadow-sm">
                    <div>
                        <!-- Estrelas Douradas -->
                        <div class="flex items-center gap-1 text-amber-400 mb-5">
                            @for ($i = 0; $i < 5; $i++)
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            @endfor
                        </div>
                        <p class="text-slate-600 font-body italic text-sm leading-relaxed mb-6">
                            "Antes eu perdia horas montando PDFs no editor de texto. Agora faço o orçamento no painel em menos de 2 minutos e a qualidade do layout final é impecável. Meus clientes elogiam o profissionalismo."
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-cta/15 text-cta flex items-center justify-center font-heading font-semibold text-sm">
                            MA
                        </div>
                        <div>
                            <h4 class="font-heading font-semibold text-sm text-text-dark">Marcos Andrade</h4>
                            <span class="text-xs text-slate-400 font-body">Desenvolvedor Freelancer</span>
                        </div>
                    </div>
                </div>

                <!-- Depoimento 2 -->
                <div class="bg-secondary border border-slate-100 rounded-3xl p-8 flex flex-col justify-between shadow-sm">
                    <div>
                        <!-- Estrelas Douradas -->
                        <div class="flex items-center gap-1 text-amber-400 mb-5">
                            @for ($i = 0; $i < 5; $i++)
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            @endfor
                        </div>
                        <p class="text-slate-600 font-body italic text-sm leading-relaxed mb-6">
                            "A gestão de status mudou nossa operação. Consigo ver rapidamente quem já aprovou o orçamento e faturar a primeira parcela do projeto imediatamente. O ROI foi quase instantâneo."
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-cta/15 text-cta flex items-center justify-center font-heading font-semibold text-sm">
                            CS
                        </div>
                        <div>
                            <h4 class="font-heading font-semibold text-sm text-text-dark">Camila Silva</h4>
                            <span class="text-xs text-slate-400 font-body">Sócia na Agência Digital Flow</span>
                        </div>
                    </div>
                </div>

                <!-- Depoimento 3 -->
                <div class="bg-secondary border border-slate-100 rounded-3xl p-8 flex flex-col justify-between shadow-sm">
                    <div>
                        <!-- Estrelas Douradas -->
                        <div class="flex items-center gap-1 text-amber-400 mb-5">
                            @for ($i = 0; $i < 5; $i++)
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            @endfor
                        </div>
                        <p class="text-slate-600 font-body italic text-sm leading-relaxed mb-6">
                            "A facilidade de cadastrar serviços recorrentes e produtos pré-definidos no sistema nos poupa centenas de digitações repetidas. Sem dúvidas, é a melhor ferramenta para prestadores de serviços."
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-cta/15 text-cta flex items-center justify-center font-heading font-semibold text-sm">
                            RO
                        </div>
                        <div>
                            <h4 class="font-heading font-semibold text-sm text-text-dark">Ricardo Oliveira</h4>
                            <span class="text-xs text-slate-400 font-body">Diretor Financeiro na Construtora Alfa</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Final Section (Fundo Azul/Roxo Gradiente) -->
    <section class="relative bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 py-20 text-white overflow-hidden text-center">
        <!-- Efeito sutil de luz de fundo -->
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_30%,rgba(255,255,255,0.08),transparent_50%)] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <!-- Título -->
            <h2 class="font-heading font-bold text-3xl md:text-4xl lg:text-5xl leading-tight mb-4 tracking-tight max-w-4xl mx-auto">
                Pare de perder vendas por orçamentos mal apresentados
            </h2>
            <!-- Subtítulo -->
            <p class="text-slate-100/90 text-sm md:text-base font-body mb-10 max-w-2xl mx-auto">
                Junte-se a 2.500+ profissionais que já aumentaram suas conversões em até 40% • <span class="font-semibold">Últimas vagas disponíveis</span>
            </p>

            <!-- Botões -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <!-- Botão 1: Começar Agora - É Grátis / Acessar Painel -->
                @auth
                    <a href="{{ route('dashboard') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 bg-white hover:bg-slate-50 text-cta font-bold text-sm md:text-base px-8 py-4 rounded-full shadow-lg shadow-black/10 hover:shadow-black/15 hover:-translate-y-0.5 transition-all duration-200 cursor-pointer">
                        <svg class="w-4 h-4 text-cta" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 0 0 6.16-12.12A14.98 14.98 0 0 0 9.63 8.41a14.98 14.98 0 0 0-6.16 12.12c2.03.35 4.07.26 6.02-.27m6.1-5.9a9 9 0 0 1-6.1-6.1m6.1 6.1A3.75 3.75 0 1 1 12.3 8.4m0 0a3.75 3.75 0 0 0-2.67-2.67m6.1 6.1L9.63 8.41"></path>
                        </svg>
                        <span>Acessar Painel Administrativo</span>
                    </a>
                @else
                    <a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 bg-white hover:bg-slate-50 text-cta font-bold text-sm md:text-base px-8 py-4 rounded-full shadow-lg shadow-black/10 hover:shadow-black/15 hover:-translate-y-0.5 transition-all duration-200 cursor-pointer">
                        <svg class="w-4 h-4 text-cta" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 0 0 6.16-12.12A14.98 14.98 0 0 0 9.63 8.41a14.98 14.98 0 0 0-6.16 12.12c2.03.35 4.07.26 6.02-.27m6.1-5.9a9 9 0 0 1-6.1-6.1m6.1 6.1A3.75 3.75 0 1 1 12.3 8.4m0 0a3.75 3.75 0 0 0-2.67-2.67m6.1 6.1L9.63 8.41"></path>
                        </svg>
                        <span>Começar Agora - É Grátis</span>
                    </a>
                @endauth
                <!-- Botão 2: Ver Planos -->
                <a href="#planos" class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 bg-transparent hover:bg-white/10 text-white border-2 border-white font-bold text-sm md:text-base px-8 py-3.5 rounded-full hover:-translate-y-0.5 transition-all duration-200 cursor-pointer">
                    <!-- Diamante SVG -->
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 3h12l4 6-10 12L2 9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 3 8 9l4 12 4-12-3-6"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2 9h20"></path>
                    </svg>
                    <span>Ver Planos</span>
                </a>
            </div>

            <!-- Benefícios / Rodapé do CTA -->
            <div class="flex flex-wrap items-center justify-center gap-4 md:gap-6 mt-10 text-xs md:text-sm font-medium text-slate-100/90 border-t border-white/10 pt-8 max-w-3xl mx-auto">
                <div class="flex items-center gap-1.5">
                    <!-- Escudo SVG -->
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"></path>
                    </svg>
                    <span>14 dias grátis</span>
                </div>
                <span class="w-1 h-1 rounded-full bg-white/40"></span>
                <span>Sem cartão de crédito</span>
                <span class="w-1 h-1 rounded-full bg-white/40"></span>
                <span>Cancele quando quiser</span>
                <span class="w-1 h-1 rounded-full bg-white/40"></span>
                <span class="italic text-slate-200">Oferta limitada</span>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-slate-100 py-12 bg-secondary">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-6 text-sm text-slate-500 font-body">
            <div class="flex items-center gap-2 font-heading font-bold text-lg tracking-tight text-text-dark">
                <svg class="w-5 h-5 text-cta" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"></path>
                </svg>
                <span class="flex items-center">Gerador de <span class="text-cta ml-1">Orçamentos</span></span>
            </div>
            <p>&copy; 2026 Gerador de Orçamentos. Todos os direitos reservados.</p>
            <div class="flex items-center gap-6">
                <a href="#" class="hover:text-cta transition-colors duration-150">Termos de Uso</a>
                <a href="#" class="hover:text-cta transition-colors duration-150">Privacidade</a>
            </div>
        </div>
    </footer>

    <!-- Script de Cookies simples -->
    <script>
        function acceptCookies() {
            const banner = document.getElementById('cookies-banner');
            banner.classList.add('translate-y-4', 'opacity-0');
            setTimeout(() => {
                banner.remove();
            }, 300);
        }
    </script>
</body>
</html>
