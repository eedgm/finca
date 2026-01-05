@csrf

<x-inputs.group class="w-full">
    <x-inputs.text
        name="name"
        label="Nombre"
        value="{{ old('name', ($breed->name ?? '')) }}"
        maxlength="255"
        required
    ></x-inputs.text>
</x-inputs.group>

<x-inputs.group class="w-full">
    <x-inputs.textarea
        name="description"
        label="Descripción"
        maxlength="65535"
    >{{ old('description', ($breed->description ?? '')) }}</x-inputs.textarea>
</x-inputs.group>

