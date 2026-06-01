<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased h-full text-slate-700" x-data="{ sidebarOpen: false }">
        <div class="min-h-full">
            <!-- Mobile Off-Canvas Sidebar Backdrop -->
            <div x-show="sidebarOpen" 
                 x-transition.opacity 
                 class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm lg:hidden"
                 style="display: none;"
                 @click="sidebarOpen = false"></div>

            <!-- Mobile Off-Canvas Sidebar Panel -->
            <div x-show="sidebarOpen" 
                 x-transition:enter="transition ease-out duration-300 transform" 
                 x-transition:enter-start="-translate-x-full" 
                 x-transition:enter-end="translate-x-0" 
                 x-transition:leave="transition ease-in duration-200 transform" 
                 x-transition:leave-start="translate-x-0" 
                 x-transition:leave-end="-translate-x-full" 
                 class="fixed inset-y-0 left-0 z-50 w-64 bg-white lg:hidden flex flex-col"
                 style="display: none;">
                @include('layouts.sidebar')
            </div>

            <!-- Desktop Static Sidebar -->
            <div class="hidden lg:flex lg:w-64 lg:flex-col lg:fixed lg:inset-y-0 lg:left-0 lg:z-40">
                @include('layouts.sidebar')
            </div>

            <!-- Main Layout Wrapper -->
            <div class="lg:pl-64 flex flex-col min-h-screen">
                <!-- Top Header Bar -->
                <header class="sticky top-0 z-30 h-16 bg-white border-b border-gray-150 flex items-center justify-between px-6 lg:px-8">
                    <!-- Mobile Hamburger Menu Button -->
                    <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none p-1.5 rounded-lg hover:bg-slate-50 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"></path>
                        </svg>
                    </button>

                    <!-- Top Bar Left Side: System title/breadcrumb for visual elegance -->
                    <div class="hidden sm:flex items-center gap-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        <span>{{ config('app.name', 'Gerador de Orçamentos') }}</span>
                        <span>/</span>
                        <span class="text-gray-600 font-bold">{{ request()->segment(1) ? ucfirst(request()->segment(1)) : 'Dashboard' }}</span>
                    </div>

                    <!-- Top Bar Right Side: Profile dropdown link -->
                    <div class="flex items-center gap-4">
                        <a href="{{ route('profile.edit') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900 transition-colors flex items-center gap-2">
                            <span>Olá, <strong>{{ auth()->user()->name }}</strong></span>
                        </a>
                    </div>
                </header>

                <!-- Page Header (Breeze Standard compatibility slot) -->
                @isset($header)
                    <div class="bg-white border-b border-gray-150 py-5 px-6 lg:px-8">
                        <div class="max-w-7xl mx-auto">
                            {{ $header }}
                        </div>
                    </div>
                @endisset

                <!-- Main Content Slot -->
                <main class="flex-1 py-8 px-6 lg:px-8 bg-slate-50">
                    <div class="max-w-7xl mx-auto">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
