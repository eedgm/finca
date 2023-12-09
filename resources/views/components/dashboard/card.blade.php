@props(['bg' => 'bg-gray-800', 'title'])

<div {{ $attributes->merge(['class' => 'w-full relative flex flex-col bg-white break-words shadow-xl']) }}>
    <h2 class="px-6 py-3 text-white {{ $bg }} rounded-t-lg">{{ $title }}</h2>
    <div class="px-5 py-2">
        {{ $slot }}
    </div>
</div>
