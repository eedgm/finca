<div>
    <div class="mb-5 mt-0">
        <div class="flex flex-wrap justify-between items-center mb-4">
            <div class="md:w-1/2">
                <h3 class="text-lg font-semibold text-gray-700">
                    Vacas por Tipo
                </h3>
            </div>
            <div class="md:w-1/2 text-right flex gap-2 justify-end">
                <button
                    wire:click="openFiltersModal"
                    class="button"
                    type="button"
                >
                    <i class="mr-1 icon ion-md-funnel"></i>
                    Filtros
                </button>
                @can('create', App\Models\Cow::class)
                <button
                    wire:click="newCow"
                    class="button button-primary"
                >
                    <i class="mr-1 icon ion-md-add"></i>
                    Agregar Vaca
                </button>
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
            <button
                wire:click="newCow"
                class="inline-block mt-4 button button-primary"
            >
                <i class="mr-1 icon ion-md-add"></i>
                Agregar Primera Vaca
            </button>
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
                                            Historial
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Foto
                                        </th>
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
                                            Color
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Marcas
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Raza
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
                                            @if($cow->histories && $cow->histories->count() > 0)
                                                <span 
                                                    class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 cursor-pointer hover:bg-green-900 hover:text-white"
                                                    wire:click="newHistory({{ $cow->id }})"
                                                    >
                                                    {{ $cow->histories->count() }}
                                                </span>
                                            @else
                                                <span 
                                                    class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 cursor-pointer hover:bg-red-900 hover:text-white"
                                                    wire:click="newHistory({{ $cow->id }})"
                                                    >
                                                    -
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                            @if($cow->picture)
                                                <button
                                                    type="button"
                                                    wire:click="zoomImage('{{ \Storage::url($cow->picture) }}', '{{ $cow->name ?? 'Vaca #' . ($cow->number ?? $cow->id) }}')"
                                                    class="cursor-pointer hover:opacity-80 transition-opacity"
                                                >
                                                    <x-partials.thumbnail
                                                        src="{{ \Storage::url($cow->picture) }}"
                                                    />
                                                </button>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    -
                                                </span>
                                            @endif
                                        </td>
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
                                            @if($cow->colors && $cow->colors->count() > 0)
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($cow->colors as $color)
                                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                                        {{ $color->name }}
                                                    </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                            @if($cow->markings && $cow->markings->count() > 0)
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($cow->markings as $marking)
                                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">
                                                        {{ $marking->name }}
                                                    </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                            @if($cow->breeds && $cow->breeds->count() > 0)
                                                @php
                                                    $predominantBreed = $cow->breeds->sortByDesc(function($breed) {
                                                        return $breed->pivot->percentage;
                                                    })->first();
                                                @endphp
                                                <div class="flex flex-col">
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 mb-1">
                                                        {{ $predominantBreed->name }}
                                                    </span>
                                                    @if($cow->breeds->count() > 1)
                                                    <span class="text-xs text-gray-500">
                                                        +{{ $cow->breeds->count() - 1 }} más
                                                    </span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-center">
                                            <div class="flex justify-center space-x-2">
                                                @can('view', $cow)
                                                <button
                                                    wire:click="viewCowGenealogy({{ $cow->id }})"
                                                    class="text-purple-600 hover:text-purple-900"
                                                    title="Ver Árbol Genealógico"
                                                >
                                                    <i class="bx bx-vector text-xl"></i>
                                                </button>
                                                <button
                                                    wire:click="viewCow({{ $cow->id }})"
                                                    class="text-blue-600 hover:text-blue-900"
                                                    title="Ver"
                                                >
                                                    <i class="icon ion-md-eye text-xl"></i>
                                                </button>
                                                @endcan
                                                @can('update', $cow)
                                                <button
                                                    wire:click="editCow({{ $cow->id }})"
                                                    class="text-green-600 hover:text-green-900"
                                                    title="Editar"
                                                >
                                                    <i class="icon ion-md-create text-xl"></i>
                                                </button>
                                                @endcan
                                                @can('create', App\Models\History::class)
                                                <button
                                                    wire:click="newHistory({{ $cow->id }})"
                                                    class="text-purple-600 hover:text-purple-900"
                                                    title="Agregar Historial"
                                                >
                                                    <i class="icon ion-md-add-circle text-xl"></i>
                                                </button>
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

    <!-- Modal para Crear/Editar Vaca -->
    <x-modal wire:model="showingCowModal">
        <div class="px-6 py-4">
            <div class="text-lg font-bold">{{ $cowModalTitle }}</div>

            <div class="mt-5">
                <x-inputs.group class="w-full">
                    <x-inputs.number
                        name="cowNumber"
                        label="Número"
                        wire:model="cowNumber"
                        max="255"
                        placeholder="Número"
                    ></x-inputs.number>
                    @error('cowNumber') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </x-inputs.group>

                <x-inputs.group class="w-full">
                    <x-inputs.text
                        name="cowName"
                        label="Nombre"
                        wire:model="cowName"
                        maxlength="255"
                        placeholder="Nombre"
                    ></x-inputs.text>
                    @error('cowName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </x-inputs.group>

                <x-inputs.group class="w-full">
                    <x-inputs.select
                        name="cowGender"
                        label="Género"
                        wire:model="cowGender"
                    >
                        <option value="male">Macho</option>
                        <option value="female">Hembra</option>
                    </x-inputs.select>
                    @error('cowGender') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </x-inputs.group>

                <x-inputs.group class="w-full">
                    <x-inputs.select
                        name="cowParentId"
                        label="Padre (Toro)"
                        wire:model="cowParentId"
                    >
                        <option value="">Seleccione un Padre</option>
                        @foreach($fathersForSelect as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </x-inputs.select>
                    @error('cowParentId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </x-inputs.group>

                <x-inputs.group class="w-full">
                    <x-inputs.select
                        name="cowMotherId"
                        label="Madre (Vaca)"
                        wire:model="cowMotherId"
                    >
                        <option value="">Seleccione una Madre</option>
                        @foreach($mothersForSelect as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </x-inputs.select>
                    @error('cowMotherId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </x-inputs.group>

                <x-inputs.group class="w-full">
                    <x-inputs.select
                        name="cowFarmId"
                        label="Finca"
                        wire:model="cowFarmId"
                        required
                    >
                        <option value="">Seleccione una Finca</option>
                        @foreach($farmsForSelect as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </x-inputs.select>
                    @error('cowFarmId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </x-inputs.group>

                <x-inputs.group class="w-full">
                    <x-inputs.text
                        name="cowOwner"
                        label="Propietario"
                        wire:model="cowOwner"
                        maxlength="255"
                        placeholder="Propietario"
                    ></x-inputs.text>
                    @error('cowOwner') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </x-inputs.group>

                <x-inputs.group class="w-full">
                    <x-inputs.checkbox
                        name="cowSold"
                        label="Vendida"
                        wire:model="cowSold"
                    ></x-inputs.checkbox>
                    @error('cowSold') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </x-inputs.group>

                <x-inputs.group class="w-full">
                    <div
                        image-url="{{ $editingCow && $cow && $cow->picture ? \Storage::url($cow->picture) : '' }}"
                        x-data="imageViewer()"
                        @refresh.window="refreshUrl()"
                    >
                        <x-inputs.partials.label
                            name="cowPicture"
                            label="Foto"
                        ></x-inputs.partials.label>
                        <br />

                        <template x-if="imageUrl">
                            <img
                                :src="imageUrl"
                                class="object-cover rounded border border-gray-200"
                                style="width: 100px; height: 100px;"
                            />
                        </template>

                        <template x-if="!imageUrl">
                            <div
                                class="border rounded border-gray-200 bg-gray-100"
                                style="width: 100px; height: 100px;"
                            ></div>
                        </template>

                        <div class="mt-2">
                            <input
                                type="file"
                                name="cowPicture"
                                id="cowPicture"
                                wire:model="cowPicture"
                                @change="fileChosen"
                            />
                        </div>
                        @error('cowPicture') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </x-inputs.group>

                <x-inputs.group class="w-full">
                    <x-inputs.date
                        name="cowBorn"
                        label="Fecha de Nacimiento"
                        wire:model="cowBorn"
                    ></x-inputs.date>
                    @error('cowBorn') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </x-inputs.group>

                <!-- Características Físicas -->
                <div class="w-full border-t border-gray-200 pt-4 mt-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Características Físicas</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-inputs.group class="w-full">
                            <x-inputs.select
                                name="cowColorIds"
                                label="Colores"
                                wire:model="cowColorIds"
                                multiple
                            >
                                @foreach($colorsForSelect as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </x-inputs.select>
                            @error('cowColorIds') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            @error('cowColorIds.*') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </x-inputs.group>

                        <x-inputs.group class="w-full">
                            <x-inputs.select
                                name="cowMarkingIds"
                                label="Marcas Distintivas"
                                wire:model="cowMarkingIds"
                                multiple
                            >
                                @foreach($markingsForSelect as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </x-inputs.select>
                            @error('cowMarkingIds') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            @error('cowMarkingIds.*') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </x-inputs.group>

                        <x-inputs.group class="w-full">
                            <x-inputs.number
                                name="cowBirthWeight"
                                label="Peso al Nacer (kg)"
                                wire:model="cowBirthWeight"
                                min="0"
                                step="0.01"
                                placeholder="0.00"
                            ></x-inputs.number>
                            @error('cowBirthWeight') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </x-inputs.group>

                        <x-inputs.group class="w-full">
                            <x-inputs.number
                                name="cowHeight"
                                label="Altura a la Cruz (cm)"
                                wire:model="cowHeight"
                                min="0"
                                step="0.01"
                                placeholder="0.00"
                            ></x-inputs.number>
                            @error('cowHeight') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </x-inputs.group>
                    </div>

                    <x-inputs.group class="w-full mt-4">
                        <x-inputs.textarea
                            name="cowObservations"
                            label="Observaciones"
                            wire:model="cowObservations"
                            rows="3"
                            placeholder="Otras características, notas, etc."
                        ></x-inputs.textarea>
                        @error('cowObservations') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </x-inputs.group>
                </div>

                <!-- Razas -->
                <x-inputs.group class="w-full">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Razas ({{ array_sum($cowBreeds) }}% / 100%)
                    </label>
                    
                    @if($cowParentId || $cowMotherId)
                    <button
                        type="button"
                        wire:click="calculateBreedsFromParents"
                        class="mb-3 text-sm text-blue-600 hover:text-blue-800 underline"
                    >
                        <i class="icon ion-md-calculator"></i> Calcular desde Padres
                    </button>
                    @endif
                    
                    <div class="space-y-2 mb-3">
                        @foreach($cowBreeds as $breedId => $percentage)
                        <div class="flex items-center gap-2 p-2 bg-gray-50 rounded">
                            <span class="flex-1 text-sm">
                                <strong>{{ $breedsForSelect[$breedId] ?? 'Raza #' . $breedId }}</strong>: {{ number_format($percentage, 2) }}%
                            </span>
                            <button
                                type="button"
                                wire:click="removeBreedFromCow({{ $breedId }})"
                                class="text-red-600 hover:text-red-800"
                            >
                                <i class="icon ion-md-close"></i>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="flex gap-2">
                        <x-inputs.select
                            name="newBreedId"
                            label="Agregar Raza"
                            wire:model="newBreedId"
                            class="flex-1"
                        >
                            <option value="">Seleccione una raza</option>
                            @foreach($breedsForSelect as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </x-inputs.select>
                        
                        <x-inputs.number
                            name="newBreedPercentage"
                            label="Porcentaje"
                            wire:model="newBreedPercentage"
                            min="0"
                            max="100"
                            step="0.01"
                            class="w-32"
                            placeholder="%"
                        ></x-inputs.number>
                        
                        <button
                            type="button"
                            wire:click="addBreedToCow"
                            class="button button-primary self-end"
                        >
                            <i class="icon ion-md-add"></i>
                        </button>
                    </div>
                    @error('newBreedId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    @error('newBreedPercentage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    @error('breeds') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    
                    @if(array_sum($cowBreeds) > 100)
                    <p class="text-red-500 text-xs mt-1">⚠️ La suma de los porcentajes excede 100%</p>
                    @elseif(array_sum($cowBreeds) < 100 && !empty($cowBreeds))
                    <p class="text-yellow-600 text-xs mt-1">⚠️ La suma de los porcentajes es menor a 100%</p>
                    @endif
                </x-inputs.group>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 flex justify-between">
            <button
                type="button"
                class="button"
                wire:click="closeModals"
            >
                <i class="mr-1 icon ion-md-close"></i>
                Cancelar
            </button>

            <button
                type="button"
                class="button button-primary"
                wire:click="saveCow"
            >
                <i class="mr-1 icon ion-md-save"></i>
                Guardar
            </button>
        </div>
    </x-modal>

    <!-- Modal para Ver Vaca -->
    <x-modal wire:model="showingViewCowModal">
        <div class="px-6 py-4">
            <div class="text-lg font-bold">Detalles de la Vaca</div>

            @if($cow)
            <div class="mt-5 space-y-4">
                <div>
                    <h5 class="font-medium text-gray-700">Número</h5>
                    <span>{{ $cow->number ?? '-' }}</span>
                </div>
                <div>
                    <h5 class="font-medium text-gray-700">Nombre</h5>
                    <span>{{ $cow->name ?? '-' }}</span>
                </div>
                <div>
                    <h5 class="font-medium text-gray-700">Género</h5>
                    <span>{{ $cow->gender === 'male' ? 'Macho' : 'Hembra' }}</span>
                </div>
                <div>
                    <h5 class="font-medium text-gray-700">Finca</h5>
                    <span>{{ $cow->farm->name ?? '-' }}</span>
                </div>
                <div>
                    <h5 class="font-medium text-gray-700">ID Padre</h5>
                    <span>{{ $cow->parent_id ?? '-' }}</span>
                </div>
                <div>
                    <h5 class="font-medium text-gray-700">ID Madre</h5>
                    <span>{{ $cow->mother_id ?? '-' }}</span>
                </div>
                <div>
                    <h5 class="font-medium text-gray-700">Razas</h5>
                    @if($cow->breeds && $cow->breeds->count() > 0)
                    <div class="mt-2 space-y-1">
                        @foreach($cow->breeds as $breed)
                        <div class="text-sm">
                            <span class="font-medium">{{ $breed->name }}</span>: 
                            <span>{{ number_format($breed->pivot->percentage, 2) }}%</span>
                        </div>
                        @endforeach
                        @php
                            $predominantBreed = $cow->predominantBreed;
                        @endphp
                        @if($predominantBreed)
                        <div class="mt-2 pt-2 border-t border-gray-200">
                            <span class="text-xs font-semibold text-blue-600">
                                Raza Predominante: {{ $predominantBreed->name }} 
                                ({{ number_format($predominantBreed->pivot->percentage, 2) }}%)
                            </span>
                        </div>
                        @endif
                    </div>
                    @else
                    <span class="text-gray-400">Sin razas asignadas</span>
                    @endif
                </div>
                <div>
                    <h5 class="font-medium text-gray-700">Propietario</h5>
                    <span>{{ $cow->owner ?? '-' }}</span>
                </div>
                <div>
                    <h5 class="font-medium text-gray-700">Vendida</h5>
                    <span>{{ $cow->sold ? 'Sí' : 'No' }}</span>
                </div>
                <div>
                    <h5 class="font-medium text-gray-700">Fecha de Nacimiento</h5>
                    <span>{{ $cow->born ? $cow->born->format('d/m/Y') : '-' }}</span>
                </div>
                
                <!-- Características Físicas -->
                <div class="border-t border-gray-200 pt-4 mt-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3">Características Físicas</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h5 class="font-medium text-gray-700 mb-2">Colores</h5>
                            @if($cow->colors && $cow->colors->count() > 0)
                                <div class="flex flex-wrap gap-1">
                                    @foreach($cow->colors as $color)
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                        {{ $color->name }}
                                    </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </div>
                        <div>
                            <h5 class="font-medium text-gray-700 mb-2">Marcas Distintivas</h5>
                            @if($cow->markings && $cow->markings->count() > 0)
                                <div class="flex flex-wrap gap-1">
                                    @foreach($cow->markings as $marking)
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">
                                        {{ $marking->name }}
                                    </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </div>
                        <div>
                            <h5 class="font-medium text-gray-700">Peso al Nacer</h5>
                            <span>{{ $cow->birth_weight ? number_format($cow->birth_weight, 2) . ' kg' : '-' }}</span>
                        </div>
                        <div>
                            <h5 class="font-medium text-gray-700">Altura a la Cruz</h5>
                            <span>{{ $cow->height ? number_format($cow->height, 2) . ' cm' : '-' }}</span>
                        </div>
                    </div>
                    
                    @if($cow->observations)
                    <div class="mt-4">
                        <h5 class="font-medium text-gray-700">Observaciones</h5>
                        <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ $cow->observations }}</p>
                    </div>
                    @endif
                </div>
                
                <div>
                    <h5 class="font-medium text-gray-700 mb-2">Foto</h5>
                    @if($cow->picture)
                        <div class="flex items-center space-x-2">
                            <x-partials.thumbnail
                                src="{{ \Storage::url($cow->picture) }}"
                                size="150"
                            />
                            <button
                                wire:click="openGallery({{ $cow->id }})"
                                class="text-blue-600 hover:text-blue-900 text-sm"
                            >
                                <i class="icon ion-md-images mr-1"></i>
                                Ver Galería
                            </button>
                        </div>
                    @else
                        <span class="text-gray-400">Sin foto</span>
                    @endif
                </div>
                @if($cow->histories && $cow->histories->count() > 0)
                <div>
                    <h5 class="font-medium text-gray-700 mb-2">Historiales</h5>
                    <div class="space-y-2 max-h-64 overflow-y-auto">
                        @foreach($cow->histories as $history)
                        <div class="border border-gray-200 rounded p-2">
                            <p class="text-sm"><strong>Fecha:</strong> {{ $history->date->format('d/m/Y') }}</p>
                            @if($history->weight)
                            <p class="text-sm"><strong>Peso:</strong> {{ $history->weight }} kg</p>
                            @endif
                            @if($history->cowType)
                            <p class="text-sm"><strong>Tipo:</strong> {{ $history->cowType->name }}</p>
                            @endif
                            @if($history->comments)
                            <p class="text-sm"><strong>Comentarios:</strong> {{ $history->comments }}</p>
                            @endif
                            @if($history->picture)
                            <div class="mt-2">
                                <img 
                                    src="{{ \Storage::url($history->picture) }}" 
                                    alt="Historial {{ $history->date->format('d/m/Y') }}"
                                    class="w-20 h-20 object-cover rounded cursor-pointer"
                                    onclick="window.open('{{ \Storage::url($history->picture) }}', '_blank')"
                                />
                            </div>
                            @endif
                            @if($history->medicines && $history->medicines->count() > 0)
                            <div class="mt-2">
                                <p class="text-xs font-semibold text-gray-600">Medicamentos:</p>
                                <ul class="text-xs text-gray-600 list-disc list-inside">
                                    @foreach($history->medicines as $medicine)
                                    <li>{{ $medicine->name }} @if($medicine->pivot->cc)({{ $medicine->pivot->cc }} cc)@endif</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @endif
        </div>

        <div class="px-6 py-4 bg-gray-50 flex justify-end">
            <button
                type="button"
                class="button"
                wire:click="closeModals"
            >
                <i class="mr-1 icon ion-md-close"></i>
                Cerrar
            </button>
        </div>
    </x-modal>

    <!-- Modal para Agregar Historial -->
    <x-modal wire:model="showingHistoryModal">
        <div class="px-6 py-4">
            <div class="text-lg font-bold">Agregar Historial</div>

            @if($selectedCowId)
            <!-- Historiales Anteriores -->
            <div class="mt-4 mb-4 p-3 bg-gray-50 rounded-lg">
                <h6 class="font-semibold text-gray-700 mb-2">Historiales Anteriores ({{ $this->cowHistories->count() }})</h6>
                <div class="space-y-2 max-h-48 overflow-y-auto">
                    @foreach($this->cowHistories as $prevHistory)
                    <div class="border border-gray-200 rounded p-2 bg-white text-sm">
                        <p><strong>Fecha:</strong> {{ $prevHistory->date->format('d/m/Y') }}</p>
                        @if($prevHistory->weight)
                        <p><strong>Peso:</strong> {{ $prevHistory->weight }} kg</p>
                        @endif
                        @if($prevHistory->cowType)
                        <p><strong>Tipo:</strong> {{ $prevHistory->cowType->name }}</p>
                        @endif
                        @if($prevHistory->medicines && $prevHistory->medicines->count() > 0)
                        <p class="text-xs text-gray-600">
                            <strong>Medicamentos:</strong> 
                            @foreach($prevHistory->medicines as $medicine)
                                {{ $medicine->name }}
                                @if($medicine->pivot->cc) ({{ $medicine->pivot->cc }} cc)@endif
                                @if(!$loop->last), @endif
                            @endforeach
                        </p>
                        @endif
                    </div>
                    @endforeach
                    @if($this->cowHistories->isEmpty())
                    <p class="text-sm text-gray-500">No hay historiales anteriores</p>
                    @endif
                </div>
            </div>
            @endif

            <div class="mt-5">
                <x-inputs.group class="w-full">
                    <x-inputs.date
                        name="historyDate"
                        label="Fecha"
                        wire:model="historyDate"
                        required
                    ></x-inputs.date>
                    @error('historyDate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </x-inputs.group>

                <x-inputs.group class="w-full">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <h4 class="text-sm font-semibold text-blue-800 mb-3 flex items-center gap-2">
                            <i class="icon ion-md-calculator"></i>
                            Calcular Peso por Medidas
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">
                                    Circunferencia del Pecho (cm)
                                </label>
                                <input
                                    type="number"
                                    wire:model="historyChestCircumference"
                                    step="0.1"
                                    min="0"
                                    placeholder="Ej: 150"
                                    class="w-full rounded border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-200"
                                />
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">
                                    Longitud del Cuerpo (cm)
                                </label>
                                <input
                                    type="number"
                                    wire:model="historyBodyLength"
                                    step="0.1"
                                    min="0"
                                    placeholder="Ej: 120"
                                    class="w-full rounded border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-200"
                                />
                            </div>
                        </div>
                        <button
                            type="button"
                            wire:click="calculateWeightFromMeasurements"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors flex items-center justify-center gap-2"
                        >
                            <i class="icon ion-md-calculator"></i>
                            Calcular Peso Automáticamente
                        </button>
                        @if($historyChestCircumference && $historyBodyLength)
                        <p class="text-xs text-gray-600 mt-2 text-center">
                            Fórmula: (Circunferencia)² × Longitud / 10800
                        </p>
                        @endif
                    </div>
                    
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Peso (kg)
                    </label>
                    <div class="relative">
                        <input
                            type="number"
                            wire:model="historyWeight"
                            step="0.01"
                            min="0"
                            placeholder="Peso en kilogramos"
                            class="w-full rounded border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-200 {{ $historyWeight ? 'bg-green-50 border-green-300' : '' }}"
                        />
                        @if($historyWeight)
                        <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-green-600 text-xs font-medium">
                            ✓ Calculado
                        </span>
                        @endif
                    </div>
                    @error('historyWeight') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </x-inputs.group>

                <x-inputs.group class="w-full">
                    <x-inputs.select
                        name="historyCowTypeId"
                        label="Tipo de Vaca"
                        wire:model="historyCowTypeId"
                    >
                        <option value="">Seleccione un Tipo</option>
                        @foreach($cowTypesForSelect as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </x-inputs.select>
                    @error('historyCowTypeId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </x-inputs.group>

                <x-inputs.group class="w-full">
                    <x-inputs.textarea
                        name="historyComments"
                        label="Comentarios"
                        wire:model="historyComments"
                        maxlength="255"
                    ></x-inputs.textarea>
                    @error('historyComments') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </x-inputs.group>

                <x-inputs.group class="w-full">
                    <div
                        x-data="imageViewer()"
                    >
                        <x-inputs.partials.label
                            name="historyPicture"
                            label="Foto (se optimizará automáticamente)"
                        ></x-inputs.partials.label>
                        <br />

                        <template x-if="imageUrl">
                            <img
                                :src="imageUrl"
                                class="object-cover rounded border border-gray-200"
                                style="width: 150px; height: 150px;"
                            />
                        </template>

                        <template x-if="!imageUrl">
                            <div
                                class="border rounded border-gray-200 bg-gray-100"
                                style="width: 150px; height: 150px;"
                            ></div>
                        </template>

                        <div class="mt-2">
                            <input
                                type="file"
                                name="historyPicture"
                                id="historyPicture"
                                wire:model="historyPicture"
                                accept="image/*"
                                @change="fileChosen"
                            />
                        </div>
                        <p class="text-xs text-gray-500 mt-1">La imagen se redimensionará automáticamente a máximo 1200px</p>
                        @error('historyPicture') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </x-inputs.group>

                <!-- Medicamentos -->
                <x-inputs.group class="w-full">
                    <x-inputs.partials.label
                        name="medicines"
                        label="Medicamentos"
                    ></x-inputs.partials.label>
                    <div class="mt-2 space-y-2">
                        <div class="flex gap-2">
                            <select 
                                wire:model="selectedMedicine"
                                class="flex-1 rounded border-gray-300"
                                onchange="if(this.value) { @this.addMedicineToHistory(this.value); this.value = ''; }"
                            >
                                <option value="">Seleccionar Medicamento</option>
                                @foreach($medicinesForSelect as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if(!empty($selectedMedicines))
                        <div class="space-y-3 mt-3">
                            @foreach($selectedMedicines as $medicineId)
                            @php
                                $medicine = \App\Models\Medicine::find($medicineId);
                                $availableCc = $medicine ? ($medicine->total_cc ?? 0) : 0;
                                $usedCc = $medicineCc[$medicineId] ?? 0;
                                $remainingCc = $availableCc - $usedCc;
                            @endphp
                            <div class="relative p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border-2 {{ $errors->has('medicineCc.' . $medicineId) ? 'border-red-400 bg-red-50' : 'border-blue-200' }} shadow-sm hover:shadow-md transition-shadow">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-2">
                                            <i class="icon ion-md-medical text-blue-600 text-lg"></i>
                                            <h4 class="font-semibold text-gray-800 text-sm truncate">
                                                {{ $medicinesForSelect[$medicineId] ?? 'N/A' }}
                                            </h4>
                                        </div>
                                        
                                        <div class="grid grid-cols-2 gap-3 mb-3">
                                            <div class="bg-white rounded-md p-2 border border-gray-200">
                                                <p class="text-xs text-gray-500 mb-1">Disponible</p>
                                                <p class="text-sm font-bold text-green-600">
                                                    {{ number_format($medicineTotalCc[$medicineId] ?? 0, 2) }} cc
                                                </p>
                                            </div>
                                            @if($usedCc > 0)
                                            <div class="bg-white rounded-md p-2 border border-gray-200">
                                                <p class="text-xs text-gray-500 mb-1">Restante</p>
                                                <p class="text-sm font-bold {{ $remainingCc >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                                                    {{ number_format($remainingCc, 2) }} cc
                                                </p>
                                            </div>
                                            @endif
                                        </div>
                                        
                                        <div class="flex flex-col">
                                            <label class="text-xs font-medium text-gray-700 mb-1">
                                                Cantidad a usar (cc)
                                            </label>
                                            <div class="relative">
                                                <input
                                                    type="number"
                                                    wire:model="medicineCc.{{ $medicineId }}"
                                                    placeholder="0.00"
                                                    step="0.01"
                                                    min="0"
                                                    max="{{ $availableCc }}"
                                                    class="w-full px-3 py-2 rounded-md border-2 {{ $errors->has('medicineCc.' . $medicineId) ? 'border-red-400 focus:border-red-500 focus:ring-red-200' : 'border-gray-300 focus:border-blue-500 focus:ring-blue-200' }} text-sm font-medium focus:outline-none focus:ring-2 transition-colors"
                                                />
                                                @if($usedCc > 0 && $availableCc > 0)
                                                <div class="mt-2">
                                                    <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                                        <div 
                                                            class="h-full rounded-full transition-all duration-300 {{ $remainingCc >= 0 ? 'bg-gradient-to-r from-green-400 to-green-600' : 'bg-gradient-to-r from-red-400 to-red-600' }}"
                                                            style="width: {{ min(100, ($usedCc / $availableCc) * 100) }}%"
                                                        ></div>
                                                    </div>
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        {{ number_format(($usedCc / $availableCc) * 100, 1) }}% del total disponible
                                                    </p>
                                                </div>
                                                @endif
                                            </div>
                                            @error('medicineCc.' . $medicineId)
                                            <div class="mt-2 flex items-center gap-1 text-red-600 text-xs">
                                                <i class="icon ion-md-alert"></i>
                                                <span>{{ $message }}</span>
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <button
                                        type="button"
                                        wire:click="removeMedicineFromHistory({{ $medicineId }})"
                                        class="flex-shrink-0 p-2 text-red-500 hover:text-white hover:bg-red-500 rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-300"
                                        title="Eliminar medicamento"
                                    >
                                        <i class="icon ion-md-close text-lg"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </x-inputs.group>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 flex justify-between">
            <button
                type="button"
                class="button"
                wire:click="closeModals"
            >
                <i class="mr-1 icon ion-md-close"></i>
                Cancelar
            </button>

            <button
                type="button"
                class="button button-primary"
                wire:click="saveHistory"
            >
                <i class="mr-1 icon ion-md-save"></i>
                Guardar
            </button>
        </div>
    </x-modal>

    <!-- Modal de Galería de Imágenes -->
    <x-modal wire:model="showingGallery">
        <div class="px-6 py-4">
            <div class="flex items-center justify-between mb-4">
                <div class="text-lg font-bold">
                    Galería de Imágenes
                    <span class="text-sm font-normal text-gray-600">
                        @if(count($galleryImages) > 0)
                            {{ $currentImageIndex + 1 }} / {{ count($galleryImages) }}
                        @else
                            0
                        @endif
                    </span>
                </div>
                <button
                    type="button"
                    wire:click="closeGallery"
                    class="text-gray-600 hover:text-gray-800"
                >
                    <i class="icon ion-md-close text-2xl"></i>
                </button>
            </div>

            @if(count($galleryImages) > 0)
            <div class="relative">
                <!-- Imagen Principal -->
                <div class="mb-4">
                    @if(isset($galleryImages[$currentImageIndex]))
                    <img
                        src="{{ $galleryImages[$currentImageIndex]['url'] }}"
                        alt="{{ $galleryImages[$currentImageIndex]['title'] }}"
                        class="w-full h-auto max-h-[60vh] object-contain mx-auto rounded-lg shadow-lg"
                    />
                    <p class="text-center text-sm text-gray-600 mt-2">
                        {{ $galleryImages[$currentImageIndex]['title'] }}
                        @if(isset($galleryImages[$currentImageIndex]['date']) && is_object($galleryImages[$currentImageIndex]['date']))
                        - {{ $galleryImages[$currentImageIndex]['date']->format('d/m/Y') }}
                        @endif
                    </p>
                    @endif
                </div>

                <!-- Navegación -->
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <button
                        type="button"
                        wire:click="previousImage"
                        @if($currentImageIndex == 0) disabled @endif
                        class="button {{ $currentImageIndex == 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                    >
                        <i class="icon ion-md-arrow-back"></i>
                        Anterior
                    </button>

                    <!-- Miniaturas -->
                    <div class="flex gap-2 overflow-x-auto max-w-md px-2">
                        @foreach($galleryImages as $index => $image)
                        <button
                            type="button"
                            wire:click="$set('currentImageIndex', {{ $index }})"
                            class="flex-shrink-0"
                        >
                            <img
                                src="{{ $image['url'] }}"
                                alt="{{ $image['title'] }}"
                                class="w-16 h-16 object-cover rounded border-2 {{ $currentImageIndex == $index ? 'border-blue-500' : 'border-gray-300' }}"
                            />
                        </button>
                        @endforeach
                    </div>

                    <button
                        type="button"
                        wire:click="nextImage"
                        @if($currentImageIndex >= count($galleryImages) - 1) disabled @endif
                        class="button {{ $currentImageIndex >= count($galleryImages) - 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                    >
                        Siguiente
                        <i class="icon ion-md-arrow-forward"></i>
                    </button>
                </div>
            </div>
            @else
            <div class="text-center py-8">
                <p class="text-gray-500">No hay imágenes disponibles</p>
            </div>
            @endif
        </div>
    </x-modal>

    <!-- Modal para Filtros de Búsqueda -->
    <x-modal wire:model="showingFiltersModal">
        <div class="px-6 py-4">
            <div class="text-lg font-bold mb-4">Filtros de Búsqueda</div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Buscar por Número
                    </label>
                    <x-inputs.text
                        name="searchNumber"
                        wire:model.debounce.300ms="searchNumber"
                        placeholder="Número de vaca..."
                        autocomplete="off"
                    ></x-inputs.text>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Filtrar por Sexo
                    </label>
                    <x-inputs.select
                        name="searchGender"
                        wire:model="searchGender"
                    >
                        <option value="">Todos</option>
                        <option value="male">Macho</option>
                        <option value="female">Hembra</option>
                    </x-inputs.select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Buscar en Historial
                    </label>
                    <x-inputs.text
                        name="searchHistory"
                        wire:model.debounce.300ms="searchHistory"
                        placeholder="Comentarios, tipo, fecha..."
                        autocomplete="off"
                    ></x-inputs.text>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Filtrar por Color
                    </label>
                    <x-inputs.text
                        name="searchColor"
                        wire:model.debounce.300ms="searchColor"
                        placeholder="Buscar por color..."
                        autocomplete="off"
                    ></x-inputs.text>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Filtrar por Marcas
                    </label>
                    <x-inputs.text
                        name="searchMarkings"
                        wire:model.debounce.300ms="searchMarkings"
                        placeholder="Buscar por marcas distintivas..."
                        autocomplete="off"
                    ></x-inputs.text>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Filtrar por Raza
                    </label>
                    <x-inputs.text
                        name="searchBreed"
                        wire:model.debounce.300ms="searchBreed"
                        placeholder="Buscar por raza..."
                        autocomplete="off"
                    ></x-inputs.text>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 flex justify-between">
            <button
                type="button"
                wire:click="clearSearch"
                class="button"
            >
                <i class="mr-1 icon ion-md-close"></i>
                Limpiar Búsqueda
            </button>

            <button
                type="button"
                class="button button-primary"
                wire:click="closeFiltersModal"
            >
                <i class="mr-1 icon ion-md-checkmark"></i>
                Aplicar Filtros
            </button>
        </div>
    </x-modal>

    <!-- Modal para Zoom de Imagen -->
    <x-modal wire:model="showingImageZoom">
        <div class="px-6 py-4">
            @if($zoomedImageTitle)
            <div class="text-lg font-bold mb-4">{{ $zoomedImageTitle }}</div>
            @endif
            
            <div class="flex justify-center items-center max-h-[85vh] overflow-auto">
                @if($zoomedImageUrl)
                <img
                    src="{{ $zoomedImageUrl }}"
                    alt="{{ $zoomedImageTitle }}"
                    class="max-w-full max-h-[80vh] object-contain rounded-lg shadow-lg"
                />
                @endif
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 flex justify-end">
            <button
                type="button"
                class="button"
                wire:click="closeImageZoom"
            >
                <i class="mr-1 icon ion-md-close"></i>
                Cerrar
            </button>
        </div>
    </x-modal>

    <!-- Modal para Árbol Genealógico -->
    <x-modal wire:model="showingGenealogyModal" max-width="6xl">
        <div class="px-6 py-4">
            @if($selectedCowForGenealogy)
            <div class="max-h-[85vh] overflow-auto">
                @php
                    $cowForGenealogy = \App\Models\Cow::find($selectedCowForGenealogy);
                @endphp
                @if($cowForGenealogy)
                    @livewire('cow-genealogy-tree', ['cow' => $cowForGenealogy, 'withoutSearch' => true], key('genealogy-' . $selectedCowForGenealogy))
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500">Vaca no encontrada</p>
                    </div>
                @endif
            </div>
            @endif
        </div>

        <div class="px-6 py-4 bg-gray-50 flex justify-end">
            <button
                type="button"
                class="button"
                wire:click="closeGenealogyModal"
            >
                <i class="mr-1 icon ion-md-close"></i>
                Cerrar
            </button>
        </div>
    </x-modal>
</div>
