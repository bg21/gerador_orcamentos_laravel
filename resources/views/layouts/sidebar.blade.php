@php
    $setting = auth()->user()->companySetting;
    $primaryColor = $setting->primary_color ?? '#4f46e5'; // default indigo-600
    $companyName = $setting->company_name ?? config('app.name', 'Gerador de Orçamentos');
@endphp

<!-- Sidebar Wrapper for both mobile and desktop -->
<div class="flex flex-col h-full bg-white border-r border-gray-150">
    <!-- Brand Header -->
    <div class="h-16 flex items-center px-6 border-b border-gray-150">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            @if($setting && $setting->logo_path)
                <img src="{{ asset('storage/' . $setting->logo_path) }}" alt="{{ $companyName }}" class="max-h-9 object-contain" />
            @else
                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-sm" style="background-color: {{ $primaryColor }};">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path>
                    </svg>
                </div>
                <span class="font-bold text-lg text-gray-900 tracking-tight font-heading">{{ $companyName }}</span>
            @endif
        </a>
    </div>

    <!-- Navigation Menu -->
    <div class="flex-1 overflow-y-auto py-6 px-4 space-y-1.5">
        <!-- Dashboard Link -->
        <a href="{{ route('dashboard') }}" 
           @if(request()->routeIs('dashboard'))
               style="background-color: {{ $primaryColor }}15; color: {{ $primaryColor }};"
               class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-sm transition-all duration-200"
           @else
               class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm text-gray-600 hover:bg-slate-50 hover:text-gray-900 transition-all duration-200"
           @endif>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"></path>
            </svg>
            <span>{{ __('Dashboard') }}</span>
        </a>

        <!-- Clients Link -->
        <a href="{{ route('clients.index') }}" 
           @if(request()->routeIs('clients.*'))
               style="background-color: {{ $primaryColor }}15; color: {{ $primaryColor }};"
               class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-sm transition-all duration-200"
           @else
               class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm text-gray-600 hover:bg-slate-50 hover:text-gray-900 transition-all duration-200"
           @endif>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.386 11.386 0 0110.089 18H8.25c-1.357 0-2.612-.4-3.663-1.09l-.004-.003C3.562 16.2 3 14.885 3 13.5A4.5 4.5 0 017.5 9h1.386A9.39 9.39 0 0115 11.693M20.25 10.5c.34 0 .668.046.98.132C20.894 8.203 18.232 6.5 15 6.5c-.482 0-.952.037-1.409.108m2.158 5.892a3.75 3.75 0 10-7.5 0 3.75 3.75 0 007.5 0zM15 6.5a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"></path>
            </svg>
            <span>{{ __('Clientes') }}</span>
        </a>

        <!-- Services Link -->
        <a href="{{ route('services.index') }}" 
           @if(request()->routeIs('services.*'))
               style="background-color: {{ $primaryColor }}15; color: {{ $primaryColor }};"
               class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-sm transition-all duration-200"
           @else
               class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm text-gray-600 hover:bg-slate-50 hover:text-gray-900 transition-all duration-200"
           @endif>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766l.002-.001a2.25 2.25 0 012.247 2.977l-.004.013a2.252 2.252 0 01-1.246 1.44l-2.496 3.03m-2.207-3.663a9.753 9.753 0 00-1.5 1.5m1.5-1.5a9.753 9.753 0 011.5-1.5m-1.5 1.5H3.75A1.125 1.125 0 012.625 14c0-.621.504-1.125 1.125-1.125h9.75M10.5 8.25h9.75M10.5 4.5h9.75M3.75 8.25h1.5m-1.5 4.5h1.5m-1.5 4.5h1.5"></path>
            </svg>
            <span>{{ __('Serviços') }}</span>
        </a>

        <!-- Quotes Link -->
        <a href="{{ route('quotes.index') }}" 
           @if(request()->routeIs('quotes.*'))
               style="background-color: {{ $primaryColor }}15; color: {{ $primaryColor }};"
               class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-sm transition-all duration-200"
           @else
               class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm text-gray-600 hover:bg-slate-50 hover:text-gray-900 transition-all duration-200"
           @endif>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path>
            </svg>
            <span>{{ __('Orçamentos') }}</span>
        </a>

        <div class="pt-4 border-t border-gray-100 my-4"></div>

        <!-- Settings Link -->
        <a href="{{ route('settings.edit') }}" 
           @if(request()->routeIs('settings.*'))
               style="background-color: {{ $primaryColor }}15; color: {{ $primaryColor }};"
               class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-sm transition-all duration-200"
           @else
               class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium text-sm text-gray-600 hover:bg-slate-50 hover:text-gray-900 transition-all duration-200"
           @endif>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.43l-1.003.828c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.43l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.991l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.28z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <span>{{ __('Configurações') }}</span>
        </a>
    </div>

    <!-- Profile & Actions Footer -->
    <div class="p-4 border-t border-gray-150 bg-slate-50">
        <div class="flex items-center justify-between gap-3">
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 overflow-hidden group">
                <div class="w-10 h-10 rounded-xl bg-gray-250 flex items-center justify-center font-bold text-gray-700 select-none flex-shrink-0" style="background-color: {{ $primaryColor }}30; color: {{ $primaryColor }};">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="text-left overflow-hidden">
                    <p class="text-sm font-bold text-gray-800 truncate group-hover:text-gray-900 leading-tight">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-400 truncate leading-tight mt-0.5">{{ auth()->user()->email }}</p>
                </div>
            </a>

            <!-- Logout button -->
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors" title="{{ __('Log Out') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>
