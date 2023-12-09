<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @lang('crud.cows.index_title')
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-partials.card>
                <div class="mb-5 mt-4">
                    <div class="flex flex-wrap justify-between">
                        <div class="md:w-1/2">
                            <form>
                                <div class="flex items-center w-full">
                                    <x-inputs.text
                                        name="search"
                                        value="{{ $search ?? '' }}"
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
                            @can('create', App\Models\Cow::class)
                            <a
                                href="{{ route('cows.create') }}"
                                class="button button-primary"
                            >
                                <i class="mr-1 icon ion-md-add"></i>
                                @lang('crud.common.create')
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="block w-full overflow-auto scrolling-touch">
                    <table class="w-full max-w-full mb-4 bg-transparent">
                        <thead class="text-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-right">
                                    @lang('crud.cows.inputs.number')
                                </th>
                                <th class="px-4 py-3 text-left">
                                    @lang('crud.cows.inputs.name')
                                </th>
                                <th class="px-4 py-3 text-left">
                                    @lang('crud.cows.inputs.gender')
                                </th>
                                <th class="px-4 py-3 text-left">
                                    @lang('crud.cows.inputs.parent_id')
                                </th>
                                <th class="px-4 py-3 text-left">
                                    @lang('crud.cows.inputs.mother_id')
                                </th>
                                <th class="px-4 py-3 text-left">
                                    @lang('crud.cows.inputs.farm_id')
                                </th>
                                <th class="px-4 py-3 text-left">
                                    @lang('crud.cows.inputs.owner')
                                </th>
                                <th class="px-4 py-3 text-left">
                                    @lang('crud.cows.inputs.sold')
                                </th>
                                <th class="px-4 py-3 text-left">
                                    @lang('crud.cows.inputs.picture')
                                </th>
                                <th class="px-4 py-3 text-left">
                                    @lang('crud.cows.inputs.born')
                                </th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600">
                            @forelse($cows as $cow)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-right">
                                    {{ $cow->number ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-left">
                                    {{ $cow->name ?? '-' }}
                                </td>
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
                                    {{ optional($cow->farm)->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-left">
                                    {{ $cow->owner ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-left">
                                    {{ $cow->sold ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-left">
                                    <x-partials.thumbnail
                                        src="{{ $cow->picture ? \Storage::url($cow->picture) : '' }}"
                                    />
                                </td>
                                <td class="px-4 py-3 text-left">
                                    {{ $cow->born ?? '-' }}
                                </td>
                                <td
                                    class="px-4 py-3 text-center"
                                    style="width: 134px;"
                                >
                                    <div
                                        role="group"
                                        aria-label="Row Actions"
                                        class="
                                            relative
                                            inline-flex
                                            align-middle
                                        "
                                    >
                                        @can('update', $cow)
                                        <a
                                            href="{{ route('cows.edit', $cow) }}"
                                            class="mr-1"
                                        >
                                            <button
                                                type="button"
                                                class="button"
                                            >
                                                <i
                                                    class="icon ion-md-create"
                                                ></i>
                                            </button>
                                        </a>
                                        @endcan @can('view', $cow)
                                        <a
                                            href="{{ route('cows.show', $cow) }}"
                                            class="mr-1"
                                        >
                                            <button
                                                type="button"
                                                class="button"
                                            >
                                                <i class="icon ion-md-eye"></i>
                                            </button>
                                        </a>
                                        @endcan @can('delete', $cow)
                                        <form
                                            action="{{ route('cows.destroy', $cow) }}"
                                            method="POST"
                                            onsubmit="return confirm('{{ __('crud.common.are_you_sure') }}')"
                                        >
                                            @csrf @method('DELETE')
                                            <button
                                                type="submit"
                                                class="button"
                                            >
                                                <i
                                                    class="
                                                        icon
                                                        ion-md-trash
                                                        text-red-600
                                                    "
                                                ></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11">
                                    @lang('crud.common.no_items_found')
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="11">
                                    <div class="mt-10 px-4">
                                        {!! $cows->render() !!}
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </x-partials.card>
        </div>
    </div>
</x-app-layout>
