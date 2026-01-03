@php $editing = isset($user) @endphp

<div class="flex flex-wrap">
    <x-inputs.group class="w-full">
        <x-inputs.text
            name="name"
            label="Name"
            :value="old('name', ($editing ? $user->name : ''))"
            maxlength="255"
            placeholder="Name"
            required
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.email
            name="email"
            label="Email"
            :value="old('email', ($editing ? $user->email : ''))"
            maxlength="255"
            placeholder="Email"
            required
        ></x-inputs.email>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.password
            name="password"
            label="Password"
            maxlength="255"
            placeholder="Password"
            :required="!$editing"
        ></x-inputs.password>
    </x-inputs.group>

    @if($editing && isset($roles) && isset($permissions))
    <x-inputs.group class="w-full">
        <x-inputs.partials.label
            name="roles"
            label="Roles"
        ></x-inputs.partials.label>
        <div class="mt-2 space-y-2 max-h-48 overflow-y-auto border border-gray-200 rounded p-2">
            @foreach($roles as $role)
            <label class="flex items-center">
                <input
                    type="checkbox"
                    name="roles[]"
                    value="{{ $role->name }}"
                    {{ (isset($userRoles) && in_array($role->name, $userRoles)) ? 'checked' : '' }}
                    class="rounded border-gray-300 text-primary focus:ring-primary"
                >
                <span class="ml-2 text-sm text-gray-700">{{ $role->name }}</span>
            </label>
            @endforeach
        </div>
        @error('roles') @include('components.inputs.partials.error') @enderror
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.partials.label
            name="permissions"
            label="Permisos Directos"
        ></x-inputs.partials.label>
        <div class="mt-2 space-y-2 max-h-48 overflow-y-auto border border-gray-200 rounded p-2">
            @foreach($permissions as $permission)
            <label class="flex items-center">
                <input
                    type="checkbox"
                    name="permissions[]"
                    value="{{ $permission->name }}"
                    {{ (isset($userPermissions) && in_array($permission->name, $userPermissions)) ? 'checked' : '' }}
                    class="rounded border-gray-300 text-primary focus:ring-primary"
                >
                <span class="ml-2 text-sm text-gray-700">{{ $permission->name }}</span>
            </label>
            @endforeach
        </div>
        @error('permissions') @include('components.inputs.partials.error') @enderror
    </x-inputs.group>
    @endif
</div>
