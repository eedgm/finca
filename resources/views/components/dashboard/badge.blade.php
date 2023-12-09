@props(['background', 'title', 'total', 'icon', 'add', 'url'])

<div class="w-full mt-4">
    <div class="flex justify-between p-4 w-full {{ $background }} rounded-lg">
        <div>
            <h6 class="text-xs font-medium leading-none tracking-wider text-white uppercase">
                {{ $title }}
            </h6>
            <a href="{{ route($url) }}"><span class="text-xl font-semibold text-white">{{ $total }}</span></a>

        </div>
        <span>
            <i class="text-4xl text-white bx {{ $icon }}"></i>
        </span>
    </div>
</div>
