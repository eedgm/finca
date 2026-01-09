@props(['name'])

<a {{ $attributes->merge(['class' => 'block w-full py-2 px-2 md:px-6 clear-both whitespace-nowrap hover:text-cyan-500']) }}>
    {{ $name }}
</a>
