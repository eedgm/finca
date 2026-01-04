<div>
    <div class="mb-5 mt-0">
        <div class="flex flex-wrap justify-between items-center mb-4">
            <div class="md:w-1/2">
                <h3 class="text-lg font-semibold text-gray-700">
                    Árbol Genealógico
                </h3>
            </div>
        </div>

        @if(!$withoutSearch)
        <div class="bg-white rounded-lg shadow p-4 mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Seleccionar Vaca o Toro
            </label>
            <div class="flex gap-2">
                <select 
                    wire:model="selectedCowId"
                    wire:change="selectCow($event.target.value)"
                    class="flex-1 rounded border-gray-300"
                >
                    <option value="">Seleccione una vaca o toro</option>
                    @foreach($cowsForSelect as $id => $label)
                    <option value="{{ $id }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @endif
    </div>

    @if($selectedCow && !empty($genealogyTree))
    <div class="bg-white rounded-lg shadow p-6 overflow-x-auto">
        <div class="min-w-full">
            <!-- Tree Visualization -->
            <div class="flex flex-col items-center space-y-8">
                <!-- Grandparents Row (Level 3) -->
                @if(($genealogyTree['parent'] && ($genealogyTree['parent']['parent'] || $genealogyTree['parent']['mother'])) || 
                    ($genealogyTree['mother'] && ($genealogyTree['mother']['parent'] || $genealogyTree['mother']['mother'])))
                <div class="w-full">
                    <h4 class="text-sm font-semibold text-gray-600 mb-4 text-center">Bisabuelos</h4>
                    <div class="flex justify-center gap-4 flex-wrap">
                        @if($genealogyTree['parent'])
                            @if($genealogyTree['parent']['parent'])
                            <div class="flex flex-col items-center">
                                <div class="text-xs text-gray-500 mb-1">Abuelo Paterno</div>
                                @include('livewire.partials.genealogy-node', [
                                    'node' => $genealogyTree['parent']['parent'],
                                    'isRoot' => false
                                ])
                            </div>
                            @endif
                            @if($genealogyTree['parent']['mother'])
                            <div class="flex flex-col items-center">
                                <div class="text-xs text-gray-500 mb-1">Abuela Paterna</div>
                                @include('livewire.partials.genealogy-node', [
                                    'node' => $genealogyTree['parent']['mother'],
                                    'isRoot' => false
                                ])
                            </div>
                            @endif
                        @endif

                        @if($genealogyTree['mother'])
                            @if($genealogyTree['mother']['parent'])
                            <div class="flex flex-col items-center">
                                <div class="text-xs text-gray-500 mb-1">Abuelo Materno</div>
                                @include('livewire.partials.genealogy-node', [
                                    'node' => $genealogyTree['mother']['parent'],
                                    'isRoot' => false
                                ])
                            </div>
                            @endif
                            @if($genealogyTree['mother']['mother'])
                            <div class="flex flex-col items-center">
                                <div class="text-xs text-gray-500 mb-1">Abuela Materna</div>
                                @include('livewire.partials.genealogy-node', [
                                    'node' => $genealogyTree['mother']['mother'],
                                    'isRoot' => false
                                ])
                            </div>
                            @endif
                        @endif
                    </div>
                </div>
                @endif

                <!-- Parents Row (Level 2) -->
                @if($genealogyTree['parent'] || $genealogyTree['mother'])
                <div class="w-full">
                    <h4 class="text-sm font-semibold text-gray-600 mb-4 text-center">Padres</h4>
                    <div class="flex justify-center gap-8 flex-wrap">
                        @if($genealogyTree['parent'])
                        <div class="flex flex-col items-center">
                            <div class="text-xs text-gray-500 mb-1">Padre (Toro)</div>
                            @include('livewire.partials.genealogy-node', [
                                'node' => $genealogyTree['parent'],
                                'isRoot' => false
                            ])
                        </div>
                        @endif

                        @if($genealogyTree['mother'])
                        <div class="flex flex-col items-center">
                            <div class="text-xs text-gray-500 mb-1">Madre (Vaca)</div>
                            @include('livewire.partials.genealogy-node', [
                                'node' => $genealogyTree['mother'],
                                'isRoot' => false
                            ])
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Connecting Lines -->
                @if($genealogyTree['parent'] || $genealogyTree['mother'])
                <div class="flex justify-center w-full">
                    <div class="border-t-2 border-gray-300 w-32"></div>
                </div>
                @endif

                <!-- Root Node (Selected Cow) - Level 1 -->
                <div class="w-full">
                    <h4 class="text-sm font-semibold text-gray-600 mb-4 text-center">Vaca Seleccionada</h4>
                    <div class="flex justify-center">
                        @include('livewire.partials.genealogy-node', [
                            'node' => $genealogyTree,
                            'isRoot' => true
                        ])
                    </div>
                </div>

                <!-- Connecting Lines to Children -->
                @if(!empty($genealogyTree['children']))
                <div class="flex justify-center w-full">
                    <div class="border-t-2 border-gray-300 w-32"></div>
                </div>
                @endif

                <!-- Children (Level 0) -->
                @if(!empty($genealogyTree['children']))
                <div class="w-full">
                    <h4 class="text-sm font-semibold text-gray-600 mb-4 text-center">Hijos/Hijas ({{ count($genealogyTree['children']) }})</h4>
                    <div class="flex flex-wrap justify-center gap-3">
                        @foreach($genealogyTree['children'] as $child)
                        @php
                            $childCow = \App\Models\Cow::find($child['id']);
                        @endphp
                        <button
                            type="button"
                            wire:click="selectCow({{ $child['id'] }})"
                            class="bg-blue-50 hover:bg-blue-100 rounded-lg p-3 border-2 border-blue-200 transition-all cursor-pointer hover:shadow-md hover:scale-105 flex flex-col items-center"
                            title="Hacer clic para ver el árbol de esta vaca"
                        >
                            @if($childCow && $childCow->picture)
                            <img 
                                src="{{ \Storage::url($childCow->picture) }}" 
                                alt="{{ $child['name'] ?? 'Vaca' }}"
                                class="w-12 h-12 object-cover rounded-full border-2 border-blue-300 mb-2"
                            />
                            @else
                            <div class="w-12 h-12 {{ $child['gender'] === 'male' ? 'bg-blue-500' : 'bg-pink-500' }} rounded-full mb-2 flex items-center justify-center">
                                <span class="text-white text-lg">{{ $child['gender'] === 'male' ? '♂' : '♀' }}</span>
                            </div>
                            @endif
                            <div class="text-xs font-medium text-gray-700 mb-1">
                                {{ $child['number'] ? '#' . $child['number'] : '' }} {{ $child['name'] ?? 'Sin nombre' }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $child['gender'] === 'male' ? '♂ Macho' : '♀ Hembra' }}
                            </div>
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Siblings -->
                @if(!empty($genealogyTree['siblings']))
                <div class="w-full mt-8 pt-8 border-t border-gray-200">
                    <h4 class="text-sm font-semibold text-gray-600 mb-4 text-center">Hermanos/Hermanas ({{ count($genealogyTree['siblings']) }})</h4>
                    <div class="flex flex-wrap justify-center gap-3">
                        @foreach($genealogyTree['siblings'] as $sibling)
                        @php
                            $siblingCow = \App\Models\Cow::find($sibling['id']);
                        @endphp
                        <button
                            type="button"
                            wire:click="selectCow({{ $sibling['id'] }})"
                            class="bg-gray-50 hover:bg-gray-100 rounded-lg p-3 border border-gray-200 transition-all cursor-pointer hover:shadow-md flex flex-col items-center"
                            title="Hacer clic para ver el árbol de esta vaca"
                        >
                            @if($siblingCow && $siblingCow->picture)
                            <img 
                                src="{{ \Storage::url($siblingCow->picture) }}" 
                                alt="{{ $sibling['name'] ?? 'Vaca' }}"
                                class="w-12 h-12 object-cover rounded-full border-2 border-gray-300 mb-2"
                            />
                            @else
                            <div class="w-12 h-12 {{ $sibling['gender'] === 'male' ? 'bg-blue-500' : 'bg-pink-500' }} rounded-full mb-2 flex items-center justify-center">
                                <span class="text-white text-lg">{{ $sibling['gender'] === 'male' ? '♂' : '♀' }}</span>
                            </div>
                            @endif
                            <div class="text-xs font-medium text-gray-600 mb-1">
                                {{ $sibling['number'] ? '#' . $sibling['number'] : '' }} {{ $sibling['name'] ?? 'Sin nombre' }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $sibling['gender'] === 'male' ? '♂ Macho' : '♀ Hembra' }}
                            </div>
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @elseif($selectedCowId)
    <div class="bg-white rounded-lg shadow p-6 text-center">
        <p class="text-gray-500">No se encontró información genealógica para esta vaca.</p>
    </div>
    @else
    <div class="bg-white rounded-lg shadow p-6 text-center">
        <p class="text-gray-500">Seleccione una vaca o toro para ver su árbol genealógico.</p>
    </div>
    @endif
</div>

