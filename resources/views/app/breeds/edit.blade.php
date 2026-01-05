<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Raza
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-partials.card>
                <x-slot name="title">
                    <a href="{{ route('breeds.index') }}" class="mr-4">
                        <i class="mr-1 icon ion-md-arrow-back"></i>
                    </a>
                </x-slot>

                <x-form method="PUT" action="{{ route('breeds.update', $breed) }}" class="mt-4">
                    @include('app.breeds.form-inputs')

                    <div class="mt-10">
                        <a href="{{ route('breeds.index') }}" class="button">
                            <i class="mr-1 icon ion-md-return-left text-primary"></i>
                            Volver
                        </a>

                        <a href="{{ route('breeds.create') }}" class="button">
                            <i class="mr-1 icon ion-md-add text-primary"></i>
                            Crear
                        </a>

                        <button type="submit" class="button button-primary float-right">
                            <i class="mr-1 icon ion-md-save"></i>
                            Actualizar
                        </button>
                    </div>
                </x-form>
            </x-partials.card>
        </div>
    </div>
</x-app-layout>

