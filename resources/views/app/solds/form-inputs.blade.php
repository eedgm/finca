@php $editing = isset($sold) @endphp

<div class="flex flex-wrap">
    <x-inputs.group class="w-full">
        <x-inputs.date
            name="date"
            label="Date"
            value="{{ old('date', ($editing ? optional($sold->date)->format('Y-m-d') : '')) }}"
            max="255"
            required
        ></x-inputs.date>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.select name="cow_id" label="Cow" required>
            @php $selected = old('cow_id', ($editing ? $sold->cow_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Cow</option>
            @foreach($cows as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.number
            name="pounds"
            label="Pounds"
            :value="old('pounds', ($editing ? $sold->pounds : ''))"
            max="255"
            step="0.01"
            placeholder="Pounds"
        ></x-inputs.number>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.number
            name="kilograms"
            label="Kilograms"
            :value="old('kilograms', ($editing ? $sold->kilograms : ''))"
            max="255"
            step="0.01"
            placeholder="Kilograms"
        ></x-inputs.number>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.number
            name="price"
            label="Price"
            :value="old('price', ($editing ? $sold->price : ''))"
            max="255"
            step="0.01"
            placeholder="Price"
        ></x-inputs.number>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.text
            name="number_sold"
            label="Number Sold"
            :value="old('number_sold', ($editing ? $sold->number_sold : ''))"
            maxlength="255"
            placeholder="Number Sold"
        ></x-inputs.text>
    </x-inputs.group>
</div>
