<div>
    <div class="mb-5 mt-4">
        <div class="flex flex-wrap justify-between">
            <div class="md:w-1/2">
                <form wire:submit.prevent="$refresh">
                    <div class="flex items-center w-full">
                        <x-inputs.text
                            name="search"
                            wire:model.debounce.300ms="search"
                            placeholder="{{ __('crud.common.search') }}"
                            autocomplete="off"
                        ></x-inputs.text>

                        <div class="ml-1">
                            <button
                                type="submit"
                                class="button button-primary"
                            >
                                <i class="icon ion-md-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="md:w-1/2 text-right">
                @can('create', App\Models\Medicine::class)
                <button
                    wire:click="newMedicine"
                    class="button button-primary"
                >
                    <i class="mr-1 icon ion-md-add"></i>
                    @lang('crud.common.create')
                </button>
                @endcan
            </div>
        </div>
    </div>

    <div class="block w-full overflow-auto scrolling-touch">
        <table class="w-full max-w-full mb-4 bg-transparent">
            <thead class="text-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left">
                        @lang('crud.medicines.inputs.name')
                    </th>
                    <th class="px-4 py-3 text-left">
                        @lang('crud.medicines.inputs.manufacturer_id')
                    </th>
                    <th class="px-4 py-3 text-left">
                        @lang('crud.medicines.inputs.expiration_date')
                    </th>
                    <th class="px-4 py-3 text-left">
                        @lang('crud.medicines.inputs.code')
                    </th>
                    <th class="px-4 py-3 text-right">
                        @lang('crud.medicines.inputs.cc')
                    </th>
                    <th class="px-4 py-3 text-right">
                        @lang('crud.medicines.inputs.cost')
                    </th>
                    <th class="px-4 py-3 text-left">
                        @lang('crud.medicines.inputs.market_id')
                    </th>
                    <th class="px-4 py-3 text-left">
                        Foto
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="text-gray-600">
                @forelse($medicines as $medicine)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-left">
                        {{ $medicine->name ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-left">
                        {{ optional($medicine->manufacturer)->name ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-left">
                        {{ $medicine->expiration_date ? $medicine->expiration_date->format('d/m/Y') : '-' }}
                    </td>
                    <td class="px-4 py-3 text-left">
                        {{ $medicine->code ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-right">
                        {{ $medicine->cc ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-right">
                        {{ $medicine->cost ? number_format($medicine->cost, 2) : '-' }}
                    </td>
                    <td class="px-4 py-3 text-left">
                        {{ optional($medicine->market)->name ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-left">
                        @if($medicine->picture)
                            <x-partials.thumbnail
                                src="{{ \Storage::url($medicine->picture) }}"
                            />
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center" style="width: 134px;">
                        <div role="group" aria-label="Row Actions" class="relative inline-flex align-middle">
                            @can('view', $medicine)
                            <button
                                wire:click="viewMedicine({{ $medicine->id }})"
                                class="mr-1 button"
                                title="Ver"
                            >
                                <i class="icon ion-md-eye"></i>
                            </button>
                            @endcan
                            @can('update', $medicine)
                            <button
                                wire:click="editMedicine({{ $medicine->id }})"
                                class="mr-1 button"
                                title="Editar"
                            >
                                <i class="icon ion-md-create"></i>
                            </button>
                            @endcan
                            @can('delete', $medicine)
                            <button
                                wire:click="deleteMedicine({{ $medicine->id }})"
                                wire:confirm="{{ __('crud.common.are_you_sure') }}"
                                class="button"
                                title="Eliminar"
                            >
                                <i class="icon ion-md-trash text-red-600"></i>
                            </button>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        @lang('crud.common.no_items_found')
                    </td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="9">
                        <div class="mt-10 px-4">
                            {{ $medicines->links() }}
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Modal para Crear/Editar Medicina -->
    <x-modal wire:model="showingModal">
        <div class="px-6 py-4 max-h-[90vh] overflow-y-auto">
            <div class="text-lg font-bold">{{ $modalTitle }}</div>

            <div class="mt-5">
                <x-inputs.group class="w-full">
                    <x-inputs.text
                        name="medicineName"
                        label="Nombre"
                        wire:model="medicineName"
                        maxlength="255"
                        placeholder="Nombre"
                        required
                    ></x-inputs.text>
                    @error('medicineName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </x-inputs.group>

                <x-inputs.group class="w-full">
                    <div class="flex items-center justify-between mb-2">
                        <x-inputs.partials.label
                            name="medicineManufacturerId"
                            label="Fabricante"
                        ></x-inputs.partials.label>
                        @can('create', App\Models\Manufacturer::class)
                        <button
                            type="button"
                            wire:click="newManufacturer"
                            class="bg-green-600 text-white px-2 py-1 rounded-md text-sm hover:bg-green-700"
                            title="Nuevo Fabricante"
                        >
                            <i class="mr-1 icon ion-md-add text-white"></i>
                            Agregar
                        </button>
                        @endcan
                    </div>
                    <x-inputs.select
                        name="medicineManufacturerId"
                        wire:model="medicineManufacturerId"
                        required
                    >
                        <option value="">Seleccione un Fabricante</option>
                        @foreach($manufacturersForSelect as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </x-inputs.select>
                    @error('medicineManufacturerId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </x-inputs.group>

                <x-inputs.group class="w-full">
                    <x-inputs.date
                        name="medicineExpirationDate"
                        label="Fecha de Vencimiento"
                        wire:model="medicineExpirationDate"
                    ></x-inputs.date>
                    @error('medicineExpirationDate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </x-inputs.group>

                <x-inputs.group class="w-full">
                    <x-inputs.text
                        name="medicineCode"
                        label="Código"
                        wire:model="medicineCode"
                        maxlength="255"
                        placeholder="Código"
                    ></x-inputs.text>
                    @error('medicineCode') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </x-inputs.group>

                <x-inputs.group class="w-full">
                    <x-inputs.number
                        name="medicineCc"
                        label="CC"
                        wire:model="medicineCc"
                        step="0.01"
                        placeholder="CC"
                    ></x-inputs.number>
                    @error('medicineCc') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </x-inputs.group>

                <x-inputs.group class="w-full">
                    <x-inputs.number
                        name="medicineCost"
                        label="Costo"
                        wire:model="medicineCost"
                        step="0.01"
                        placeholder="Costo"
                    ></x-inputs.number>
                    @error('medicineCost') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </x-inputs.group>

                <x-inputs.group class="w-full">
                    <div class="flex items-center justify-between mb-2">
                        <x-inputs.partials.label
                            name="medicineMarketId"
                            label="Tienda"
                        ></x-inputs.partials.label>
                        @can('create', App\Models\Market::class)
                        <button
                            type="button"
                            wire:click="newMarket"
                            class="bg-green-600 text-white px-2 py-1 rounded-md text-sm hover:bg-green-700"
                            title="Nueva Tienda"
                        >
                            <i class="mr-1 icon ion-md-add text-white"></i>
                            Agregar
                        </button>
                        @endcan
                    </div>
                    <x-inputs.select
                        name="medicineMarketId"
                        wire:model="medicineMarketId"
                        required
                    >
                        <option value="">Seleccione una Tienda</option>
                        @foreach($marketsForSelect as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </x-inputs.select>
                    @error('medicineMarketId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </x-inputs.group>

                <x-inputs.group class="w-full">
                    <div
                        x-data="imageViewer('{{ $editing && $medicine && $medicine->picture ? \Storage::url($medicine->picture) : '' }}')"
                    >
                        <x-inputs.partials.label
                            name="medicinePicture"
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
                                name="medicinePicture"
                                id="medicinePicture"
                                wire:model="medicinePicture"
                                @change="fileChosen"
                            />
                        </div>
                        @error('medicinePicture') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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
                wire:click="saveMedicine"
            >
                <i class="mr-1 icon ion-md-save"></i>
                Guardar
            </button>
        </div>
    </x-modal>

    <!-- Modal para Ver Medicina -->
    <x-modal wire:model="showingViewModal">
        <div class="px-6 py-4">
            <div class="text-lg font-bold">Detalles de la Medicina</div>

            @if($medicine)
            <div class="mt-5 space-y-4">
                <div>
                    <h5 class="font-medium text-gray-700">Nombre</h5>
                    <span>{{ $medicine->name ?? '-' }}</span>
                </div>
                <div>
                    <h5 class="font-medium text-gray-700">Fabricante</h5>
                    <span>{{ $medicine->manufacturer->name ?? '-' }}</span>
                </div>
                <div>
                    <h5 class="font-medium text-gray-700">Fecha de Vencimiento</h5>
                    <span>{{ $medicine->expiration_date ? $medicine->expiration_date->format('d/m/Y') : '-' }}</span>
                </div>
                <div>
                    <h5 class="font-medium text-gray-700">Código</h5>
                    <span>{{ $medicine->code ?? '-' }}</span>
                </div>
                <div>
                    <h5 class="font-medium text-gray-700">CC</h5>
                    <span>{{ $medicine->cc ?? '-' }}</span>
                </div>
                <div>
                    <h5 class="font-medium text-gray-700">Costo</h5>
                    <span>{{ $medicine->cost ? number_format($medicine->cost, 2) : '-' }}</span>
                </div>
                <div>
                    <h5 class="font-medium text-gray-700">Tienda</h5>
                    <span>{{ $medicine->market->name ?? '-' }}</span>
                </div>
                <div>
                    <h5 class="font-medium text-gray-700">Foto</h5>
                    @if($medicine->picture)
                        <x-partials.thumbnail
                            src="{{ \Storage::url($medicine->picture) }}"
                            size="150"
                        />
                    @else
                        <span class="text-gray-400">Sin foto</span>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <div class="px-6 py-4 bg-gray-50 flex justify-end">
            <button
                type="button"
                class="button"
                wire:click="$toggle('showingViewModal')"
            >
                <i class="mr-1 icon ion-md-close"></i>
                Cerrar
            </button>
        </div>
    </x-modal>

    <!-- Modal para Nuevo Fabricante -->
    <x-modal wire:model="showingManufacturerModal">
        <div class="px-6 py-4">
            <div class="text-lg font-bold">Nuevo Fabricante</div>

            <div class="mt-5">
                <x-inputs.group class="w-full">
                    <x-inputs.text
                        name="newManufacturerName"
                        label="Nombre del Fabricante"
                        wire:model="newManufacturerName"
                        maxlength="255"
                        placeholder="Nombre"
                        required
                    ></x-inputs.text>
                    @error('newManufacturerName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </x-inputs.group>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 flex justify-between">
            <button
                type="button"
                class="button"
                wire:click="$toggle('showingManufacturerModal')"
            >
                <i class="mr-1 icon ion-md-close"></i>
                Cancelar
            </button>

            <button
                type="button"
                class="button button-primary"
                wire:click="saveManufacturer"
            >
                <i class="mr-1 icon ion-md-save"></i>
                Guardar
            </button>
        </div>
    </x-modal>

    <!-- Modal para Nueva Tienda -->
    <x-modal wire:model="showingMarketModal">
        <div class="px-6 py-4">
            <div class="text-lg font-bold">Nueva Tienda</div>

            <div class="mt-5">
                <x-inputs.group class="w-full">
                    <x-inputs.text
                        name="newMarketName"
                        label="Nombre de la Tienda"
                        wire:model="newMarketName"
                        maxlength="255"
                        placeholder="Nombre"
                        required
                    ></x-inputs.text>
                    @error('newMarketName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </x-inputs.group>

                <x-inputs.group class="w-full">
                    <x-inputs.text
                        name="newMarketPhone"
                        label="Teléfono"
                        wire:model="newMarketPhone"
                        maxlength="255"
                        placeholder="Teléfono"
                    ></x-inputs.text>
                    @error('newMarketPhone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </x-inputs.group>

                <x-inputs.group class="w-full">
                    <x-inputs.text
                        name="newMarketDirection"
                        label="Dirección"
                        wire:model="newMarketDirection"
                        maxlength="255"
                        placeholder="Dirección"
                    ></x-inputs.text>
                    @error('newMarketDirection') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </x-inputs.group>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 flex justify-between">
            <button
                type="button"
                class="button"
                wire:click="$toggle('showingMarketModal')"
            >
                <i class="mr-1 icon ion-md-close"></i>
                Cancelar
            </button>

            <button
                type="button"
                class="button button-primary"
                wire:click="saveMarket"
            >
                <i class="mr-1 icon ion-md-save"></i>
                Guardar
            </button>
        </div>
    </x-modal>
</div>

