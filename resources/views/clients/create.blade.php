<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cadastrar Cliente') }}
        </h2>
    </x-slot>

    <div class="py-2">
        
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-150 p-6">
                <form method="POST" action="{{ route('clients.store') }}" class="space-y-6">
                    @csrf

                    <!-- Nome -->
                    <div>
                        <x-input-label for="name" :value="__('Nome / Razão Social *')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <!-- Documento -->
                    <div>
                        <x-input-label for="document" :value="__('CPF / CNPJ')" />
                        <x-text-input id="document" name="document" type="text" class="mt-1 block w-full" :value="old('document')" />
                        <x-input-error class="mt-2" :messages="$errors->get('document')" />
                    </div>

                    <!-- E-mail -->
                    <div>
                        <x-input-label for="email" :value="__('E-mail')" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>

                    <!-- Telefone -->
                    <div>
                        <x-input-label for="phone" :value="__('Telefone')" />
                        <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone')" />
                        <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                    </div>

                    <!-- Endereço -->
                    <div>
                        <x-input-label for="address" :value="__('Endereço')" />
                        <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address')" />
                        <x-input-error class="mt-2" :messages="$errors->get('address')" />
                    </div>

                    <!-- Botões -->
                    <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
                        <x-primary-button>{{ __('Salvar Cliente') }}</x-primary-button>
                        <a href="{{ route('clients.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Cancelar') }}
                        </a>
                    </div>
                </form>
            </div>
    </div>
</x-app-layout>
