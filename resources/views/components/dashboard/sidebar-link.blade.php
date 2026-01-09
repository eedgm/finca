@props(['name', 'active', 'icon', 'id' => false])

@php
$classes = ($active ?? false)
            ? 'relative flex items-center justify-between w-full py-1 rounded truncate text-white bg-green-700'
            : 'relative flex items-center justify-between w-full py-1 rounded truncate hover:text-white hover:bg-green-700';
@endphp
<li class="relative">
    <a {{ $attributes->merge(['class' => $classes]) }}
        @if ($id)
            :class="{ 'text-cyan-500 bg-slate-700': selected == {{ $id }} }"
            @click="selected !== {{ $id }} ? selected = {{ $id }} : selected = null"
        @endif
        class="flex items-center justify-between overflow-hidden rounded hover:bg-slate-700 hover:text-cyan-500"
        >
        <div>
            <i class="bx {{ $icon }} text-lg md:text-3xl h-6 w-4 md:h-10 md:w-10 flex-shrink-0"></i>
            <span class="text-xs font-medium duration-300 ease-in-out lg:text-lg" :class="isSidebarExpanded ? 'inline' : 'hidden group-hover:inline'">{{ $name }}</span>
        </div>
        @if ($id)
            <div class="relative float-right mt-3" :class="isSidebarExpanded ? 'inline' : 'hidden group-hover:inline'">
                <i class="transition duration-300 transform -rotate-90 bx bx-chevron-down" :class="{ 'rotate-0': selected == {{ $id }}, '-rotate-90': !(selected == {{ $id }}) }"></i>
            </div>
        @endif
    </a>
    @if ($id)
        <ul x-show="selected == {{ $id }}" x-transition:enter="transition-all duration-200 ease-out" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="text-xs lg:text-lg block rounded rounded-t-none top-full z-50 pl-1 md:pl-6 py-0.5 text-left mb-1 font-normal" style="display: none;">
            {{ $slot }}
        </ul>
    @endif
</li>
