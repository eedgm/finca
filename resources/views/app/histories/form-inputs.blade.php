@php $editing = isset($history) @endphp

<div class="flex flex-wrap">
    <x-inputs.group class="w-full">
        <x-inputs.date
            name="date"
            label="Date"
            value="{{ old('date', ($editing ? optional($history->date)->format('Y-m-d') : '')) }}"
            max="255"
            required
        ></x-inputs.date>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.number
            name="weight"
            label="Weight"
            :value="old('weight', ($editing ? $history->weight : ''))"
            step="0.01"
            placeholder="Weight"
        ></x-inputs.number>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.select name="cow_type_id" label="Cow Type">
            @php $selected = old('cow_type_id', ($editing ? $history->cow_type_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Cow Type</option>
            @foreach($cowTypes as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.textarea name="comments" label="Comments" maxlength="255"
            >{{ old('comments', ($editing ? $history->comments : ''))
            }}</x-inputs.textarea
        >
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <div
            x-data="imageViewer('{{ $editing && $history->picture ? \Storage::url($history->picture) : '' }}')"
        >
            <x-inputs.partials.label
                name="picture"
                label="Picture"
            ></x-inputs.partials.label
            ><br />

            <!-- Show the image -->
            <template x-if="imageUrl">
                <img
                    :src="imageUrl"
                    class="object-cover border border-gray-200 rounded"
                    style="width: 100px; height: 100px;"
                />
            </template>

            <!-- Show the gray box when image is not available -->
            <template x-if="!imageUrl">
                <div
                    class="bg-gray-100 border border-gray-200 rounded"
                    style="width: 100px; height: 100px;"
                ></div>
            </template>

            <div class="mt-2">
                <input
                    type="file"
                    name="picture"
                    id="picture"
                    @change="fileChosen"
                />
            </div>

            @error('picture') @include('components.inputs.partials.error')
            @enderror
        </div>
    </x-inputs.group>
</div>
