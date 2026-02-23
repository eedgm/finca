<div class="min-h-full">
    {{-- Header móvil: título y botón --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-6">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-800 tracking-tight">
            Ventas de animales
        </h1>
        @can('create', App\Models\Sale::class)
        @if(!$showingForm)
        <button
            type="button"
            wire:click="openForm"
            class="w-full sm:w-auto min-h-[44px] flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold shadow-lg shadow-emerald-600/25 active:scale-[0.98] transition"
        >
            <i class="bx bx-plus-circle text-xl"></i>
            <span>Nueva venta</span>
        </button>
        @endif
        @endcan
    </div>

    @if (session()->has('message'))
    <div class="mb-4 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
        {{ session('message') }}
    </div>
    @endif

    {{-- Formulario crear venta (pantalla completa en móvil, card en desktop) --}}
    @if($showingForm)
    <div class="mb-8 rounded-2xl border border-gray-200 bg-white shadow-lg overflow-hidden">
        <div class="sticky top-0 z-10 flex items-center justify-between px-4 sm:px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800">{{ $editingSaleId ? 'Editar venta' : 'Nueva venta' }}</h2>
            <button
                type="button"
                wire:click="closeForm"
                class="p-2 rounded-lg text-gray-500 hover:bg-gray-200 hover:text-gray-700 transition"
                aria-label="Cerrar"
            >
                <i class="bx bx-x text-2xl"></i>
            </button>
        </div>

        <div class="p-4 sm:p-6 space-y-6 max-h-[75vh] overflow-y-auto">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-inputs.group class="w-full">
                    <x-inputs.partials.label name="saleDate" label="Fecha de venta" />
                    <input type="date" id="saleDate" wire:model="saleDate"
                        class="block w-full py-2.5 px-3 text-base border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" />
                    @error('saleDate') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </x-inputs.group>
                <x-inputs.group class="w-full">
                    <x-inputs.partials.label name="farmId" label="Finca (opcional)" />
                    <select id="farmId" wire:model="farmId"
                        class="block w-full py-2.5 px-3 text-base border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        @foreach($farmsForSelect as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </x-inputs.group>
            </div>

            <x-inputs.group class="w-full">
                <x-inputs.partials.label name="notes" label="Notas (opcional)" />
                <textarea id="notes" wire:model="notes" rows="2" placeholder="Notas..."
                    class="block w-full py-2.5 px-3 text-base border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
            </x-inputs.group>

            {{-- Animales --}}
            <div>
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-base font-semibold text-gray-800">Animales</h3>
                    <button type="button" wire:click="addAnimal"
                        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium transition">
                        <i class="bx bx-plus"></i>
                        Agregar animal
                    </button>
                </div>

                <p class="text-sm text-gray-500 mb-3">Peso y precio son opcionales; puedes completarlos después al editar la venta.</p>
                <div class="space-y-4">
                    @foreach($animals as $index => $animal)
                    <div wire:key="animal-{{ $editingSaleId ?? 'new' }}-{{ $index }}" class="p-4 rounded-xl border border-gray-200 bg-gray-50/50 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600">Animal {{ $index + 1 }}</span>
                            @if(count($animals) > 1)
                            <button type="button" wire:click="removeAnimal({{ $index }})"
                                class="p-1.5 rounded-lg text-red-600 hover:bg-red-50 transition" aria-label="Quitar">
                                <i class="bx bx-trash text-lg"></i>
                            </button>
                            @endif
                        </div>
                        <div class="grid grid-cols-1 xs:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Descripción (opcional)</label>
                                <input type="text" wire:model.defer="animals.{{ $index }}.description" placeholder="Ej. Novillo #1"
                                    class="block w-full py-2 px-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" />
                            </div>
                            <div></div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Peso (kg) — opcional</label>
                                <input type="number" step="0.01" min="0" wire:model.live="animals.{{ $index }}.weight_kg" placeholder="Completar después"
                                    class="block w-full py-2 px-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" />
                                @error("animals.{$index}.weight_kg") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                @if(isset($animal['weight_kg']) && $animal['weight_kg'] !== '' && is_numeric($animal['weight_kg']))
                                <p class="mt-1 text-xs text-gray-500">
                                    ≈ {{ number_format((float)$animal['weight_kg'] * $this->kgToLbs, 2) }} lb
                                </p>
                                @endif
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Precio por kg (USD) — opcional</label>
                                <input type="number" step="0.01" min="0" wire:model.live="animals.{{ $index }}.price_per_kg_usd" placeholder="Completar después"
                                    class="block w-full py-2 px-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" />
                                @error("animals.{$index}.price_per_kg_usd") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                @if(isset($animal['weight_kg']) && isset($animal['price_per_kg_usd']) && $animal['weight_kg'] !== '' && $animal['price_per_kg_usd'] !== '' && is_numeric($animal['weight_kg']) && is_numeric($animal['price_per_kg_usd']))
                                <p class="mt-1 text-xs font-semibold text-emerald-700">
                                    Total línea: ${{ number_format((float)$animal['weight_kg'] * (float)$animal['price_per_kg_usd'], 2) }}
                                </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Resumen: subtotal, menos 4%, total --}}
            <div class="rounded-xl bg-gray-100 p-4 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="font-medium">${{ number_format($this->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Menos {{ $this->taxPercent }}%</span>
                    <span class="font-medium">−${{ number_format($this->taxAmount, 2) }}</span>
                </div>
                <div class="flex justify-between text-base font-bold text-gray-800 pt-2 border-t border-gray-300">
                    <span>Total</span>
                    <span>${{ number_format($this->total, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="sticky bottom-0 flex gap-3 px-4 sm:px-6 py-4 bg-gray-50 border-t border-gray-200">
            <button type="button" wire:click="closeForm"
                class="flex-1 sm:flex-none min-h-[48px] px-5 py-3 rounded-xl border border-gray-300 bg-white text-gray-700 font-medium hover:bg-gray-50 transition">
                Cancelar
            </button>
            <button type="button" wire:click="save"
                class="flex-1 sm:flex-none min-h-[48px] px-5 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold shadow-lg shadow-emerald-600/25 transition">
                <i class="bx bx-check mr-1"></i> {{ $editingSaleId ? 'Guardar cambios' : 'Guardar venta' }}
            </button>
        </div>
    </div>
    @endif

    {{-- Listado de ventas --}}
    <div class="space-y-4">
        @forelse($sales as $sale)
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
            <div class="flex items-stretch">
                <button type="button" wire:click="viewSale({{ $sale->id }})" class="flex-1 text-left p-4 sm:p-5 block hover:bg-gray-50/80 transition">
                    <div class="flex flex-wrap items-start justify-between gap-2">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $sale->sale_date->format('d/m/Y') }}</p>
                            <p class="text-sm text-gray-500">{{ $sale->farm?->name ?? 'Sin finca' }} · {{ $sale->saleAnimals->count() }} animal(es)</p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-emerald-700">${{ number_format($sale->total, 2) }}</p>
                            <p class="text-xs text-gray-500">Total (menos {{ $sale::TAX_PERCENT }}%)</p>
                        </div>
                    </div>
                </button>
                @can('update', $sale)
                <button type="button" wire:click="openEditForm({{ $sale->id }})" class="p-4 flex items-center justify-center text-gray-500 hover:bg-gray-100 hover:text-emerald-600 transition" title="Editar venta">
                    <i class="bx bx-edit text-xl"></i>
                </button>
                @endcan
            </div>
        </div>
        @empty
        <div class="rounded-2xl border border-dashed border-gray-300 bg-gray-50/50 p-8 text-center">
            <i class="bx bx-cart-alt text-4xl text-gray-400 mb-3"></i>
            <p class="text-gray-600 font-medium">No hay ventas registradas</p>
            <p class="text-sm text-gray-500 mt-1">Crea tu primera venta con el botón «Nueva venta»</p>
        </div>
        @endforelse
    </div>

    @if($sales->hasPages())
    <div class="mt-6">
        {{ $sales->links() }}
    </div>
    @endif

    {{-- Modal ver venta --}}
    <x-modal wire:model="showingViewModal">
        @if($saleToView)
        <div class="px-4 sm:px-6 py-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-800">Venta del {{ $saleToView->sale_date->format('d/m/Y') }}</h2>
                <button type="button" wire:click="closeViewModal" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100">
                    <i class="bx bx-x text-xl"></i>
                </button>
            </div>
            <p class="text-sm text-gray-500 mb-4">{{ $saleToView->farm?->name ?? 'Sin finca' }}</p>
            @if($saleToView->notes)
            <p class="text-sm text-gray-600 mb-4">{{ $saleToView->notes }}</p>
            @endif

            <div class="space-y-3 mb-4">
                @foreach($saleToView->saleAnimals as $item)
                <div class="flex flex-wrap items-center justify-between gap-2 p-3 rounded-xl bg-gray-50 border border-gray-100">
                    <div>
                        <p class="font-medium text-gray-800">{{ $item->description ?: 'Animal' }}</p>
                        <p class="text-sm text-gray-500">
                            @if($item->hasWeightAndPrice())
                                {{ number_format($item->weight_kg, 2) }} kg · {{ number_format($item->weight_lbs, 2) }} lb · ${{ number_format($item->price_per_kg_usd, 2) }}/kg
                            @else
                                Peso y precio pendientes
                            @endif
                        </p>
                    </div>
                    <p class="font-semibold text-emerald-700">{{ $item->line_total !== null ? '$' . number_format($item->line_total, 2) : '—' }}</p>
                </div>
                @endforeach
            </div>

            <div class="rounded-xl bg-gray-100 p-4 space-y-1">
                <div class="flex justify-between text-sm"><span class="text-gray-600">Subtotal</span><span>${{ number_format($saleToView->subtotal, 2) }}</span></div>
                <div class="flex justify-between text-sm"><span class="text-gray-600">Menos {{ $saleToView::TAX_PERCENT }}%</span><span>−${{ number_format($saleToView->tax_amount, 2) }}</span></div>
                <div class="flex justify-between font-bold text-gray-800 pt-2 border-t border-gray-300"><span>Total</span><span>${{ number_format($saleToView->total, 2) }}</span></div>
            </div>

            <div class="mt-4 flex flex-wrap gap-3">
                @can('update', $saleToView)
                <button type="button" wire:click="openEditForm({{ $saleToView->id }})"
                    class="min-h-[44px] px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-medium transition">
                    <i class="bx bx-edit mr-1"></i> Editar venta
                </button>
                @endcan
                @can('delete', $saleToView)
                <button type="button" wire:click="deleteSale({{ $saleToView->id }})" wire:confirm="¿Eliminar esta venta?"
                    class="min-h-[44px] px-4 py-2 rounded-xl border border-red-200 bg-red-50 text-red-700 font-medium hover:bg-red-100 transition">
                    <i class="bx bx-trash mr-1"></i> Eliminar
                </button>
                @endcan
                <button type="button" wire:click="closeViewModal"
                    class="min-h-[44px] px-4 py-2 rounded-xl border border-gray-300 bg-white text-gray-700 font-medium hover:bg-gray-50 transition">
                    Cerrar
                </button>
            </div>
        </div>
        @endif
    </x-modal>
</div>
