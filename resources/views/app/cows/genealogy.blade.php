<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Árbol Genealógico de Vacas
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-partials.card>
                @livewire('cow-genealogy-tree')
            </x-partials.card>
        </div>
    </div>
</x-app-layout>

