@php
    $genderColor = $node['gender'] === 'male' ? 'bg-blue-50 border-blue-400 text-blue-900' : 'bg-pink-50 border-pink-400 text-pink-900';
    $genderIcon = $node['gender'] === 'male' ? '♂' : '♀';
    $genderBadge = $node['gender'] === 'male' ? 'bg-blue-500 text-white' : 'bg-pink-500 text-white';
@endphp

<div class="inline-block">
    <div class="bg-white rounded-xl border-2 {{ $isRoot ? 'border-purple-500 shadow-xl ring-4 ring-purple-200' : 'border-gray-300 shadow-md' }} p-4 min-w-[220px] max-w-[220px] {{ $genderColor }} hover:shadow-lg transition-all duration-200">
        @if($isRoot)
        <div class="text-xs font-bold text-purple-700 mb-2 text-center bg-purple-100 rounded-full py-1 px-2">
            ⭐ VACA SELECCIONADA
        </div>
        @endif
        
        <div class="text-center mb-3">
            @if(isset($node['picture']) && $node['picture'])
            <div class="mx-auto mb-2">
                <img 
                    src="{{ \Storage::url($node['picture']) }}" 
                    alt="{{ $node['name'] ?? 'Vaca' }}"
                    class="w-20 h-20 object-cover rounded-full border-4 border-white shadow-md mx-auto cursor-pointer hover:scale-110 transition-transform"
                    onclick="window.open('{{ \Storage::url($node['picture']) }}', '_blank')"
                    title="Hacer clic para ver imagen completa"
                />
            </div>
            @else
            <div class="w-20 h-20 {{ $genderBadge }} rounded-full mx-auto mb-2 flex items-center justify-center shadow-md">
                <span class="text-3xl">{{ $genderIcon }}</span>
            </div>
            @endif
        </div>
        
        <div class="text-center">
            <div class="font-bold text-sm mb-1 text-gray-800">
                @if($node['number'])
                    <span class="text-gray-600">#</span>{{ $node['number'] }}
                @endif
                @if($node['name'])
                    <div class="mt-1">{{ $node['name'] }}</div>
                @endif
            </div>
            <div class="inline-block px-2 py-1 rounded-full text-xs font-medium {{ $genderBadge }} mb-2">
                {{ $node['gender'] === 'male' ? 'Macho' : 'Hembra' }}
            </div>
            @if($node['born'])
            <div class="text-xs text-gray-600 mt-2 bg-gray-100 rounded px-2 py-1">
                📅 {{ \Carbon\Carbon::parse($node['born'])->format('d/m/Y') }}
            </div>
            @endif
        </div>
    </div>
</div>

