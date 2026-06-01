<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Serviços & Produtos') }}
            </h2>
            <a href="{{ route('services.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                </svg>
                {{ __('Novo Serviço') }}
            </a>
        </div>
    </x-slot>

    <div class="py-2">
        
            <!-- Flash Message -->
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-150">
                @if ($services->isEmpty())
                    <!-- Empty State -->
                    <div class="p-12 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 text-indigo-500 mb-4 border border-indigo-100">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">{{ __('Nenhum serviço cadastrado') }}</h3>
                        <p class="text-gray-500 text-sm mb-6 max-w-sm mx-auto">
                            {{ __('Crie um catálogo com seus serviços e preços para agilizar a criação dos seus orçamentos.') }}
                        </p>
                        <a href="{{ route('services.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-md uppercase tracking-widest transition duration-150">
                            {{ __('Cadastrar meu primeiro serviço') }}
                        </a>
                    </div>
                @else
                    <!-- Table List -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-slate-50 border-b border-gray-150">
                                <tr>
                                    <th scope="col" class="px-6 py-4 font-semibold">{{ __('Nome') }}</th>
                                    <th scope="col" class="px-6 py-4 font-semibold">{{ __('Descrição') }}</th>
                                    <th scope="col" class="px-6 py-4 font-semibold">{{ __('Preço Padrão') }}</th>
                                    <th scope="col" class="px-6 py-4 font-semibold text-right">{{ __('Ações') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($services as $service)
                                    <tr class="bg-white hover:bg-slate-50 transition duration-150">
                                        <td class="px-6 py-4 font-medium text-gray-900">
                                            {{ $service->name }}
                                        </td>
                                        <td class="px-6 py-4 text-gray-600 max-w-xs truncate">
                                            {{ $service->description ?: '-' }}
                                        </td>
                                        <td class="px-6 py-4 font-semibold text-gray-900">
                                            R$ {{ number_format($service->default_price, 2, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-2">
                                            <a href="{{ route('services.edit', $service) }}" class="inline-flex items-center px-2.5 py-1.5 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                {{ __('Editar') }}
                                            </a>
                                            <form action="{{ route('services.destroy', $service) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este serviço?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-2.5 py-1.5 bg-red-50 border border-red-200 rounded-md font-semibold text-xs text-red-700 uppercase tracking-widest shadow-sm hover:bg-red-100 hover:border-red-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                    {{ __('Excluir') }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($services->hasPages())
                        <div class="px-6 py-4 bg-slate-50 border-t border-gray-150">
                            {{ $services->links() }}
                        </div>
                    @endif
                @endif
            </div>
    </div>
</x-app-layout>
