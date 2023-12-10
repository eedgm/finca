<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            @lang('crud.medicines.create_title')
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-partials.card>
                <x-slot name="title">
                    <a href="{{ route('medicines.index') }}" class="mr-4"
                        ><i class="mr-1 icon ion-md-arrow-back"></i
                    ></a>
                </x-slot>

                <x-form
                    method="POST"
                    action="{{ route('medicines.store') }}"
                    class="mt-4"
                    has-files
                >
                    @include('app.medicines.form-inputs')

                    <div class="mt-10">
                        <a href="{{ route('medicines.index') }}" class="button">
                            <i
                                class="mr-1  icon ion-md-return-left text-primary"
                            ></i>
                            @lang('crud.common.back')
                        </a>

                        <button
                            type="submit"
                            class="float-right button button-primary"
                        >
                            <i class="mr-1 icon ion-md-save"></i>
                            @lang('crud.common.create')
                        </button>
                    </div>
                </x-form>
            </x-partials.card>
        </div>
    </div>
</x-app-layout>
