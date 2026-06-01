<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Serviço / Produto') }}
        </h2>
    </x-slot>

    <div class="py-2">
        
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-150 p-6">
                <form method="POST" action="{{ route('services.update', $service) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Nome -->
                    <div>
                        <x-input-label for="name" :value="__('Nome do Serviço / Produto *')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $service->name)" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <!-- Preço Padrão -->
                    <div>
                        <x-input-label for="default_price" :value="__('Preço Padrão (R$) *')" />
                        <div class="relative mt-1 rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <span class="text-gray-500 sm:text-sm">R$</span>
                            </div>
                            <x-text-input id="default_price" name="default_price" type="text" class="block w-full pl-10" :value="old('default_price', number_format($service->default_price, 2, ',', ''))" required />
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ __('Você pode digitar usando vírgula (ex: 150,00 ou 1200,50).') }}</p>
                        <x-input-error class="mt-2" :messages="$errors->get('default_price')" />
                    </div>

                    <!-- Descrição -->
                    <div>
                        <x-input-label for="description" :value="__('Descrição')" />
                        <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">{{ old('description', $service->description) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>

                    <!-- Botões -->
                    <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
                        <x-primary-button>{{ __('Atualizar Serviço') }}</x-primary-button>
                        <a href="{{ route('services.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Cancelar') }}
                        </a>
                    </div>
                </form>
            </div>
    </div>
</x-app-layout>
