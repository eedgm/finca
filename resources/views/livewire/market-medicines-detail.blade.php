<div>
    <div>
        @can('create', App\Models\Medicine::class)
        <button class="button" wire:click="newMedicine">
            <i class="mr-1 icon ion-md-add text-primary"></i>
            @lang('crud.common.new')
        </button>
        @endcan @can('delete-any', App\Models\Medicine::class)
        <button
            class="button button-danger"
             {{ empty($selected) ? 'disabled' : '' }} 
            onclick="confirm('Are you sure?') || event.stopImmediatePropagation()"
            wire:click="destroySelected"
        >
            <i class="mr-1 icon ion-md-trash text-primary"></i>
            @lang('crud.common.delete_selected')
        </button>
        @endcan
    </div>

    <x-modal wire:model="showingModal">
        <div class="px-6 py-4">
            <div class="text-lg font-bold">{{ $modalTitle }}</div>

            <div class="mt-5">
                <div>
                    <x-inputs.group class="w-full">
                        <x-inputs.text
                            name="medicine.name"
                            label="Name"
                            wire:model="medicine.name"
                            maxlength="255"
                            placeholder="Name"
                        ></x-inputs.text>
                    </x-inputs.group>

                    <x-inputs.group class="w-full">
                        <x-inputs.select
                            name="medicine.manufacturer_id"
                            label="Manufacturer"
                            wire:model="medicine.manufacturer_id"
                        >
                            <option value="null" disabled>Please select the Manufacturer</option>
                            @foreach($manufacturersForSelect as $value => $label)
                            <option value="{{ $value }}"  >{{ $label }}</option>
                            @endforeach
                        </x-inputs.select>
                    </x-inputs.group>
                    <x-inputs.group class="w-full">
                        <x-inputs.date
                            name="medicineExpirationDate"
                            label="Expiration Date"
                            wire:model="medicineExpirationDate"
                            max="255"
                        ></x-inputs.date>
                    </x-inputs.group>
                    <x-inputs.group class="w-full">
                        <x-inputs.text
                            name="medicine.code"
                            label="Code"
                            wire:model="medicine.code"
                            maxlength="255"
                            placeholder="Code"
                        ></x-inputs.text>
                    </x-inputs.group>
                    <x-inputs.group class="w-full">
                        <x-inputs.number
                            name="medicine.cc"
                            label="Cc"
                            wire:model="medicine.cc"
                            max="255"
                            step="0.01"
                            placeholder="Cc"
                        ></x-inputs.number>
                    </x-inputs.group>
                    <x-inputs.group class="w-full">
                        <x-inputs.number
                            name="medicine.cost"
                            label="Cost"
                            wire:model="medicine.cost"
                            max="255"
                            step="0.01"
                            placeholder="Cost"
                        ></x-inputs.number>
                    </x-inputs.group>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 flex justify-between">
            <button
                type="button"
                class="button"
                wire:click="$toggle('showingModal')"
            >
                <i class="mr-1 icon ion-md-close"></i>
                @lang('crud.common.cancel')
            </button>

            <button
                type="button"
                class="button button-primary"
                wire:click="save"
            >
                <i class="mr-1 icon ion-md-save"></i>
                @lang('crud.common.save')
            </button>
        </div>
    </x-modal>

    <div class="block w-full overflow-auto scrolling-touch mt-4">
        <table class="w-full max-w-full mb-4 bg-transparent">
            <thead class="text-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left w-1">
                        <input
                            type="checkbox"
                            wire:model="allSelected"
                            wire:click="toggleFullSelection"
                            title="{{ trans('crud.common.select_all') }}"
                        />
                    </th>
                    <th class="px-4 py-3 text-left">
                        @lang('crud.market_medicines.inputs.name')
                    </th>
                    <th class="px-4 py-3 text-left">
                        @lang('crud.market_medicines.inputs.manufacturer_id')
                    </th>
                    <th class="px-4 py-3 text-left">
                        @lang('crud.market_medicines.inputs.expiration_date')
                    </th>
                    <th class="px-4 py-3 text-left">
                        @lang('crud.market_medicines.inputs.code')
                    </th>
                    <th class="px-4 py-3 text-right">
                        @lang('crud.market_medicines.inputs.cc')
                    </th>
                    <th class="px-4 py-3 text-right">
                        @lang('crud.market_medicines.inputs.cost')
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="text-gray-600">
                @foreach ($medicines as $medicine)
                <tr class="hover:bg-gray-100">
                    <td class="px-4 py-3 text-left">
                        <input
                            type="checkbox"
                            value="{{ $medicine->id }}"
                            wire:model="selected"
                        />
                    </td>
                    <td class="px-4 py-3 text-left">
                        {{ $medicine->name ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-left">
                        {{ optional($medicine->manufacturer)->name ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-left">
                        {{ $medicine->expiration_date ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-left">
                        {{ $medicine->code ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-right">
                        {{ $medicine->cc ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-right">
                        {{ $medicine->cost ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-right" style="width: 134px;">
                        <div
                            role="group"
                            aria-label="Row Actions"
                            class="relative inline-flex align-middle"
                        >
                            @can('update', $medicine)
                            <button
                                type="button"
                                class="button"
                                wire:click="editMedicine({{ $medicine->id }})"
                            >
                                <i class="icon ion-md-create"></i>
                            </button>
                            @endcan
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="7">
                        <div class="mt-10 px-4">{{ $medicines->render() }}</div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
