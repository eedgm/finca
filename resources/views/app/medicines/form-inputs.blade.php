@php $editing = isset($medicine) @endphp

<div class="flex flex-wrap">
    <x-inputs.group class="w-full">
        <x-inputs.text
            name="name"
            label="Name"
            :value="old('name', ($editing ? $medicine->name : ''))"
            maxlength="255"
            placeholder="Name"
            required
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.select name="manufacturer_id" label="Manufacturer" required>
            @php $selected = old('manufacturer_id', ($editing ? $medicine->manufacturer_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Manufacturer</option>
            @foreach($manufacturers as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.date
            name="expiration_date"
            label="Expiration Date"
            value="{{ old('expiration_date', ($editing ? optional($medicine->expiration_date)->format('Y-m-d') : '')) }}"
            max="255"
        ></x-inputs.date>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.text
            name="code"
            label="Code"
            :value="old('code', ($editing ? $medicine->code : ''))"
            maxlength="255"
            placeholder="Code"
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.number
            name="cc"
            label="Cc"
            :value="old('cc', ($editing ? $medicine->cc : ''))"
            max="255"
            step="0.01"
            placeholder="Cc"
        ></x-inputs.number>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.number
            name="cost"
            label="Cost"
            :value="old('cost', ($editing ? $medicine->cost : ''))"
            max="255"
            step="0.01"
            placeholder="Cost"
        ></x-inputs.number>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.select name="market_id" label="Market" required>
            @php $selected = old('market_id', ($editing ? $medicine->market_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Market</option>
            @foreach($markets as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <div
            x-data="imageViewer('{{ $editing && $medicine->picture ? \Storage::url($medicine->picture) : '' }}')"
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
