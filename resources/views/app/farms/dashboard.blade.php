<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-partials.card>
                <div class="mb-5 mt-4">
                    <div class="flex flex-wrap justify-between items-center">
                        <div class="md:w-1/2">
                            <h3 class="text-lg font-semibold text-gray-700">
                                Vacas por Tipo
                            </h3>
                        </div>
                        <div class="md:w-1/2 text-right">
                            @can('create', App\Models\Cow::class)
                            <a
                                href="{{ route('cows.create') }}"
                                class="button button-primary"
                            >
                                <i class="mr-1 icon ion-md-add"></i>
                                Agregar Vaca
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>

                @if($cowsByType->isEmpty())
                    <div class="text-center py-12">
                        <p class="text-gray-500 text-lg">
                            No hay vacas registradas aún.
                        </p>
                        @can('create', App\Models\Cow::class)
                        <a
                            href="{{ route('cows.create') }}"
                            class="inline-block mt-4 button button-primary"
                        >
                            <i class="mr-1 icon ion-md-add"></i>
                            Agregar Primera Vaca
                        </a>
                        @endcan
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach($cowsByType as $typeName => $cows)
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <div class="bg-green-800 px-4 py-3">
                                    <h4 class="text-lg font-semibold text-white">
                                        {{ $typeName }} 
                                        <span class="text-sm font-normal text-gray-200">
                                            ({{ $cows->count() }} {{ $cows->count() === 1 ? 'vaca' : 'vacas' }})
                                        </span>
                                    </h4>
                                </div>
                                <div class="bg-white">
                                    <div class="overflow-x-auto">
                                        <table class="w-full">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Número
                                                    </th>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Nombre
                                                    </th>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Género
                                                    </th>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Finca
                                                    </th>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Nacimiento
                                                    </th>
                                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Foto
                                                    </th>
                                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Acciones
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($cows as $cow)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                        {{ $cow->number ?? '-' }}
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                        {{ $cow->name ?? '-' }}
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $cow->gender === 'male' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                                            {{ $cow->gender === 'male' ? 'Macho' : 'Hembra' }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                        {{ $cow->farm->name ?? '-' }}
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                        {{ $cow->born ? $cow->born->format('d/m/Y') : '-' }}
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                                        @if($cow->picture)
                                                            <x-partials.thumbnail
                                                                src="{{ \Storage::url($cow->picture) }}"
                                                            />
                                                        @else
                                                            <span class="text-gray-400">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-center">
                                                        <div class="flex justify-center space-x-2">
                                                            @can('view', $cow)
                                                            <a
                                                                href="{{ route('cows.show', $cow) }}"
                                                                class="text-blue-600 hover:text-blue-900"
                                                                title="Ver"
                                                            >
                                                                <i class="icon ion-md-eye text-xl"></i>
                                                            </a>
                                                            @endcan
                                                            @can('update', $cow)
                                                            <a
                                                                href="{{ route('cows.edit', $cow) }}"
                                                                class="text-green-600 hover:text-green-900"
                                                                title="Editar"
                                                            >
                                                                <i class="icon ion-md-create text-xl"></i>
                                                            </a>
                                                            @endcan
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </x-partials.card>
        </div>
    </div>
</x-app-layout>
