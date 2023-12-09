@php $editing = isset($cow) @endphp

<div class="flex flex-wrap">
    <x-inputs.group class="w-full">
        <x-inputs.number
            name="number"
            label="Number"
            :value="old('number', ($editing ? $cow->number : ''))"
            max="255"
            placeholder="Number"
        ></x-inputs.number>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.text
            name="name"
            label="Name"
            :value="old('name', ($editing ? $cow->name : ''))"
            maxlength="255"
            placeholder="Name"
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.select name="gender" label="Gender">
            @php $selected = old('gender', ($editing ? $cow->gender : '')) @endphp
            <option value="male" {{ $selected == 'male' ? 'selected' : '' }} >Male</option>
            <option value="female" {{ $selected == 'female' ? 'selected' : '' }} >Female</option>
        </x-inputs.select>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.text
            name="parent_id"
            label="Parent Id"
            :value="old('parent_id', ($editing ? $cow->parent_id : ''))"
            maxlength="255"
            placeholder="Parent Id"
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.text
            name="mother_id"
            label="Mother Id"
            :value="old('mother_id', ($editing ? $cow->mother_id : ''))"
            maxlength="255"
            placeholder="Mother Id"
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.select name="farm_id" label="Farm" required>
            @php $selected = old('farm_id', ($editing ? $cow->farm_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Farm</option>
            @foreach($farms as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.text
            name="owner"
            label="Owner"
            :value="old('owner', ($editing ? $cow->owner : ''))"
            maxlength="255"
            placeholder="Owner"
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.checkbox
            name="sold"
            label="Sold"
            :checked="old('sold', ($editing ? $cow->sold : 0))"
        ></x-inputs.checkbox>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <div
            x-data="imageViewer('{{ $editing && $cow->picture ? \Storage::url($cow->picture) : '' }}')"
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
                    class="object-cover rounded border border-gray-200"
                    style="width: 100px; height: 100px;"
                />
            </template>

            <!-- Show the gray box when image is not available -->
            <template x-if="!imageUrl">
                <div
                    class="border rounded border-gray-200 bg-gray-100"
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

    <x-inputs.group class="w-full">
        <x-inputs.date
            name="born"
            label="Born"
            value="{{ old('born', ($editing ? optional($cow->born)->format('Y-m-d') : '')) }}"
            max="255"
        ></x-inputs.date>
    </x-inputs.group>
</div>
