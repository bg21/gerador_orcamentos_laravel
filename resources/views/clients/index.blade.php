<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Clientes') }}
            </h2>
            <a href="{{ route('clients.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path>
                </svg>
                {{ __('Novo Cliente') }}
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
                @if ($clients->isEmpty())
                    <!-- Empty State -->
                    <div class="p-12 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 text-indigo-500 mb-4 border border-indigo-100">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.386 11.386 0 0110.089 21c-2.243 0-4.352-.64-6.136-1.753a3.555 3.555 0 01-1.08-1.079c-.015-.024-.029-.05-.044-.074A3.75 3.75 0 010 15c0-1.26.353-2.437.97-3.443M15 19.128c-.286.372-.635.705-1.037 1a11.386 11.386 0 01-3.874.628c-2.243 0-4.352-.64-6.136-1.753a3.555 3.555 0 01-1.08-1.079c-.015-.024-.029-.05-.044-.074A3.75 3.75 0 010 15c0-1.26.353-2.437.97-3.443M3 9a9 9 0 019-9 9 9 0 019 9M9.75 9.75c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm5.25 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">{{ __('Nenhum cliente cadastrado') }}</h3>
                        <p class="text-gray-500 text-sm mb-6 max-w-sm mx-auto">
                            {{ __('Comece cadastrando os clientes para os quais você deseja gerar orçamentos personalizados.') }}
                        </p>
                        <a href="{{ route('clients.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-md uppercase tracking-widest transition duration-150">
                            {{ __('Cadastrar meu primeiro cliente') }}
                        </a>
                    </div>
                @else
                    <!-- Table List -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-slate-50 border-b border-gray-150">
                                <tr>
                                    <th scope="col" class="px-6 py-4 font-semibold">{{ __('Nome') }}</th>
                                    <th scope="col" class="px-6 py-4 font-semibold">{{ __('Documento') }}</th>
                                    <th scope="col" class="px-6 py-4 font-semibold">{{ __('E-mail') }}</th>
                                    <th scope="col" class="px-6 py-4 font-semibold">{{ __('Telefone') }}</th>
                                    <th scope="col" class="px-6 py-4 font-semibold text-right">{{ __('Ações') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($clients as $client)
                                    <tr class="bg-white hover:bg-slate-50 transition duration-150">
                                        <td class="px-6 py-4 font-medium text-gray-900">
                                            {{ $client->name }}
                                        </td>
                                        <td class="px-6 py-4 text-gray-600">
                                            {{ $client->document ?: '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-gray-600">
                                            {{ $client->email ?: '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-gray-600">
                                            {{ $client->phone ?: '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-2">
                                            <a href="{{ route('clients.edit', $client) }}" class="inline-flex items-center px-2.5 py-1.5 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                {{ __('Editar') }}
                                            </a>
                                            <form action="{{ route('clients.destroy', $client) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este cliente?');">
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
                    @if ($clients->hasPages())
                        <div class="px-6 py-4 bg-slate-50 border-t border-gray-150">
                            {{ $clients->links() }}
                        </div>
                    @endif
                @endif
            </div>
    </div>
</x-app-layout>
