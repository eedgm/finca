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
        
        @if(isset($exhaustedMedicines) && $exhaustedMedicines->count() > 0)
        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
            <h4 class="text-sm font-semibold text-red-800 mb-2">
                <i class="icon ion-md-warning"></i> Medicinas Agotadas o Desechadas
            </h4>
            <div class="flex flex-wrap gap-2">
                @foreach($exhaustedMedicines as $medicine)
                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                    {{ $medicine->name }}
                    @if($medicine->discarded)
                        (Desechada)
                    @else
                        (Agotada: {{ $medicine->total_cc ?? 0 }} cc)
                    @endif
                </span>
                @endforeach
            </div>
        </div>
        @endif
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
                                                <x-partials.thumbnail
                                                    src="{{ \Storage::url($cow->picture) }}"
                                                />
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
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-center">
                                            <div class="flex justify-center space-x-2">
                                                @can('view', $cow)
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
                <h6 class="font-semibold text-gray-700 mb-2">Historiales Anteriores</h6>
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
                    <x-inputs.number
                        name="historyWeight"
                        label="Peso (kg)"
                        wire:model="historyWeight"
                        step="0.01"
                        placeholder="Peso"
                    ></x-inputs.number>
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
</div>
