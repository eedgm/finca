<div>
    <div>
        @can('create', App\Models\Cow::class)
        <button class="button" wire:click="newCow">
            <i class="mr-1 icon ion-md-add text-primary"></i>
            @lang('crud.common.new')
        </button>
        @endcan @can('delete-any', App\Models\Cow::class)
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
                        <x-inputs.number
                            name="cow.number"
                            label="Number"
                            wire:model="cow.number"
                            max="255"
                            placeholder="Number"
                        ></x-inputs.number>
                    </x-inputs.group>
                    <x-inputs.group class="w-full">
                        <x-inputs.text
                            name="cow.name"
                            label="Name"
                            wire:model="cow.name"
                            maxlength="255"
                            placeholder="Name"
                        ></x-inputs.text>
                    </x-inputs.group>

                    <x-inputs.group class="w-full">
                        <x-inputs.select
                            name="cow.gender"
                            label="Gender"
                            wire:model="cow.gender"
                        >
                            <option value="male" {{ $selected == 'male' ? 'selected' : '' }} >Male</option>
                            <option value="female" {{ $selected == 'female' ? 'selected' : '' }} >Female</option>
                        </x-inputs.select>
                    </x-inputs.group>
                    <x-inputs.group class="w-full">
                        <x-inputs.text
                            name="cow.parent_id"
                            label="Parent Id"
                            wire:model="cow.parent_id"
                            maxlength="255"
                            placeholder="Parent Id"
                        ></x-inputs.text>
                    </x-inputs.group>
                    <x-inputs.group class="w-full">
                        <x-inputs.text
                            name="cow.mother_id"
                            label="Mother Id"
                            wire:model="cow.mother_id"
                            maxlength="255"
                            placeholder="Mother Id"
                        ></x-inputs.text>
                    </x-inputs.group>
                    <x-inputs.group class="w-full">
                        <x-inputs.text
                            name="cow.owner"
                            label="Owner"
                            wire:model="cow.owner"
                            maxlength="255"
                            placeholder="Owner"
                        ></x-inputs.text>
                    </x-inputs.group>
                    <x-inputs.group class="w-full">
                        <div
                            image-url="{{ $editing && $cow->picture ? \Storage::url($cow->picture) : '' }}"
                            x-data="imageViewer()"
                            @refresh.window="refreshUrl()"
                        >
                            <x-inputs.partials.label
                                name="cowPicture"
                                label="Picture"
                            ></x-inputs.partials.label
                            ><br />

                            <!-- Show the image -->
                            <template x-if="imageUrl">
                                <img
                                    :src="imageUrl"
                                    class="
                                        object-cover
                                        rounded
                                        border border-gray-200
                                    "
                                    style="width: 100px; height: 100px;"
                                />
                            </template>

                            <!-- Show the gray box when image is not available -->
                            <template x-if="!imageUrl">
                                <div
                                    class="
                                        border
                                        rounded
                                        border-gray-200
                                        bg-gray-100
                                    "
                                    style="width: 100px; height: 100px;"
                                ></div>
                            </template>

                            <div class="mt-2">
                                <input
                                    type="file"
                                    name="cowPicture"
                                    id="cowPicture{{ $uploadIteration }}"
                                    wire:model="cowPicture"
                                    @change="fileChosen"
                                />
                            </div>

                            @error('cowPicture')
                            @include('components.inputs.partials.error')
                            @enderror
                        </div>
                    </x-inputs.group>
                    <x-inputs.group class="w-full">
                        <x-inputs.checkbox
                            name="cow.sold"
                            label="Sold"
                            wire:model="cow.sold"
                        ></x-inputs.checkbox>
                    </x-inputs.group>
                    <x-inputs.group class="w-full">
                        <x-inputs.date
                            name="cowBorn"
                            label="Born"
                            wire:model="cowBorn"
                            max="255"
                        ></x-inputs.date>
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
                    <th class="px-4 py-3 text-right">
                        @lang('crud.farm_cows.inputs.number')
                    </th>
                    <th class="px-4 py-3 text-left">
                        @lang('crud.farm_cows.inputs.name')
                    </th>
                    <th class="px-4 py-3 text-left">
                        @lang('crud.farm_cows.inputs.gender')
                    </th>
                    <th class="px-4 py-3 text-left">
                        @lang('crud.farm_cows.inputs.parent_id')
                    </th>
                    <th class="px-4 py-3 text-left">
                        @lang('crud.farm_cows.inputs.mother_id')
                    </th>
                    <th class="px-4 py-3 text-left">
                        @lang('crud.farm_cows.inputs.owner')
                    </th>
                    <th class="px-4 py-3 text-left">
                        @lang('crud.farm_cows.inputs.picture')
                    </th>
                    <th class="px-4 py-3 text-left">
                        @lang('crud.farm_cows.inputs.sold')
                    </th>
                    <th class="px-4 py-3 text-left">
                        @lang('crud.farm_cows.inputs.born')
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="text-gray-600">
                @foreach ($cows as $cow)
                <tr class="hover:bg-gray-100">
                    <td class="px-4 py-3 text-left">
                        <input
                            type="checkbox"
                            value="{{ $cow->id }}"
                            wire:model="selected"
                        />
                    </td>
                    <td class="px-4 py-3 text-right">
                        {{ $cow->number ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-left">{{ $cow->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-left">
                        {{ $cow->gender ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-left">
                        {{ $cow->parent_id ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-left">
                        {{ $cow->mother_id ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-left">
                        {{ $cow->owner ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-left">
                        <x-partials.thumbnail
                            src="{{ $cow->picture ? \Storage::url($cow->picture) : '' }}"
                        />
                    </td>
                    <td class="px-4 py-3 text-left">{{ $cow->sold ?? '-' }}</td>
                    <td class="px-4 py-3 text-left">{{ $cow->born ?? '-' }}</td>
                    <td class="px-4 py-3 text-right" style="width: 134px;">
                        <div
                            role="group"
                            aria-label="Row Actions"
                            class="relative inline-flex align-middle"
                        >
                            @can('update', $cow)
                            <button
                                type="button"
                                class="button"
                                wire:click="editCow({{ $cow->id }})"
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
                    <td colspan="10">
                        <div class="mt-10 px-4">{{ $cows->render() }}</div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
