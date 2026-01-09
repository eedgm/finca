<div>
    <div class="mb-5 mt-4 bg-gray-200 text-black p-4 rounded-lg shadow-md">
        <div class="flex flex-wrap justify-between items-center mb-4">
            <div class="w-full md:w-1/3">
                <form wire:submit.prevent="$refresh">
                    <div class="flex items-center w-full">
                        <x-inputs.text
                            name="search"
                            wire:model.debounce.300ms="search"
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
            <div class="w-full md:w-2/3 text-right space-x-2">
                @can('create', App\Models\Material::class)
                <button
                    wire:click="newMaterial"
                    class=" my-1 md:my-0 button bg-blue-600 hover:bg-blue-700 text-white"
                >
                    <i class="mr-1 icon ion-md-add-circle"></i>
                    Nuevo Material
                </button>
                @endcan
                @can('create', App\Models\InventoryMaterial::class)
                <button
                    wire:click="newTransaction('entrada')"
                    class=" my-1 md:my-0 button bg-green-600 hover:bg-green-700 text-white"
                >
                    <i class="mr-1 icon ion-md-add"></i>
                    Agregar Material
                </button>
                <button
                    wire:click="newTransaction('salida')"
                    class=" my-1 md:my-0 button bg-red-600 hover:bg-red-700 text-white"
                >
                    <i class="mr-1 icon ion-md-remove"></i>
                    Consumir Material
                </button>
                <button
                    wire:click="newTransaction('ajuste')"
                    class=" my-1 md:my-0 button bg-yellow-600 hover:bg-yellow-700 text-white"
                >
                    <i class="mr-1 icon ion-md-create"></i>
                    Ajustar Material
                </button>
                @endcan
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-gray-100 p-4 rounded-lg mb-4">
            <div class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[200px]">
                    <x-inputs.partials.label
                        name="filterType"
                        label="Tipo"
                    ></x-inputs.partials.label>
                    <x-inputs.select
                        name="filterType"
                        wire:model="filterType"
                    >
                        <option value="">Todos los tipos</option>
                        <option value="entrada">Entrada</option>
                        <option value="salida">Salida</option>
                        <option value="ajuste">Ajuste</option>
                    </x-inputs.select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <x-inputs.partials.label
                        name="filterMaterialId"
                        label="Material"
                    ></x-inputs.partials.label>
                    <x-inputs.select
                        name="filterMaterialId"
                        wire:model="filterMaterialId"
                    >
                        <option value="">Todos los materiales</option>
                        @foreach($materialsForSelect as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </x-inputs.select>
                </div>
                <div>
                    <button
                        wire:click="clearFilters"
                        class="button"
                    >
                        <i class="mr-1 icon ion-md-close"></i>
                        Limpiar Filtros
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Summary -->
    <div class="mb-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($materials as $material)
        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-800">{{ $material->name }}</h3>
                    @if($material->description)
                    <p class="text-sm text-gray-600 mt-1">{{ \Illuminate\Support\Str::limit($material->description, 50) }}</p>
                    @endif
                    <div class="mt-2">
                        <span class="text-2xl font-bold {{ $material->current_stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $material->current_stock }}
                        </span>
                        <span class="text-sm text-gray-500 ml-1">unidades</span>
                    </div>
                </div>
                @if($material->image)
                <div class="ml-4">
                    <x-partials.thumbnail
                        src="{{ \Storage::disk('public')->url(str_replace('public/', '', $material->image)) }}"
                        size="60"
                    />
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- Transactions Table -->
    <div class="block w-full overflow-auto scrolling-touch">
        <table class="w-full max-w-full mb-4 bg-transparent">
            <thead class="text-gray-700 bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">Fecha</th>
                    <th class="px-4 py-3 text-left">Material</th>
                    <th class="px-4 py-3 text-left">Tipo</th>
                    <th class="px-4 py-3 text-right">Cantidad</th>
                    <th class="px-4 py-3 text-right">Costo</th>
                    <th class="px-4 py-3 text-left">Usuario</th>
                    <th class="px-4 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-gray-600">
                @forelse($inventoryMaterials as $transaction)
                <tr class="hover:bg-gray-50 odd:bg-gray-200 even:bg-gray-300 border-b border-gray-200">
                    <td class="px-4 py-3 text-left">
                        {{ $transaction->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-4 py-3 text-left">
                        <div class="font-medium">{{ $transaction->material->name ?? '-' }}</div>
                        @if($transaction->material && $transaction->material->description)
                        <div class="text-xs text-gray-500">
                            {{ \Illuminate\Support\Str::limit($transaction->material->description, 40) }}
                        </div>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-left">
                        @if($transaction->type === 'entrada')
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 font-semibold">
                                Entrada
                            </span>
                        @elseif($transaction->type === 'salida')
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 font-semibold">
                                Salida
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 font-semibold">
                                Ajuste
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        <span class="font-semibold {{ $transaction->quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $transaction->quantity > 0 ? '+' : '' }}{{ $transaction->quantity }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        {{ $transaction->cost ? '$' . number_format($transaction->cost, 2) : '-' }}
                    </td>
                    <td class="px-4 py-3 text-left">
                        {{ $transaction->user->name ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-center" style="width: 134px;">
                        <div role="group" aria-label="Row Actions" class="relative inline-flex align-middle">
                            @can('view', $transaction)
                            <button
                                wire:click="viewTransaction({{ $transaction->id }})"
                                class="mr-1 button"
                                title="Ver"
                            >
                                <i class="icon ion-md-eye"></i>
                            </button>
                            @endcan
                            @can('update', $transaction)
                            <button
                                wire:click="editTransaction({{ $transaction->id }})"
                                class="mr-1 button"
                                title="Editar"
                            >
                                <i class="icon ion-md-create"></i>
                            </button>
                            @endcan
                            @can('delete', $transaction)
                            <button
                                wire:click="deleteTransaction({{ $transaction->id }})"
                                wire:confirm="{{ __('crud.common.are_you_sure') }}"
                                class="button"
                                title="Eliminar"
                            >
                                <i class="icon ion-md-trash text-red-600"></i>
                            </button>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                        @lang('crud.common.no_items_found')
                    </td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="7">
                        <div class="mt-10 px-4">
                            {{ $inventoryMaterials->links() }}
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Modal para Crear/Editar Transacción -->
    <x-modal wire:model="showingModal">
        <div class="px-6 py-4 max-h-[90vh] overflow-y-auto">
            <div class="text-lg font-bold">{{ $modalTitle }}</div>

            <div class="mt-5">
                <x-inputs.group class="w-full">
                    <x-inputs.select
                        name="materialId"
                        label="Material"
                        wire:model="materialId"
                        required
                    >
                        <option value="">Seleccione un Material</option>
                        @foreach($materialsForSelect as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </x-inputs.select>
                    @error('materialId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </x-inputs.group>

                @if($selectedMaterial)
                <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm text-blue-800">
                        <strong>Stock actual:</strong> 
                        <span class="font-bold">{{ $selectedMaterial->current_stock ?? 0 }}</span> unidades
                    </p>
                    @if($type === 'salida' && ($selectedMaterial->current_stock ?? 0) < 1)
                    <p class="text-sm text-red-600 mt-1">
                        <i class="icon ion-md-warning"></i> Stock insuficiente
                    </p>
                    @endif
                </div>
                @endif

                <x-inputs.group class="w-full">
                    <x-inputs.select
                        name="type"
                        label="Tipo de Transacción"
                        wire:model="type"
                        required
                    >
                        <option value="entrada">Entrada</option>
                        <option value="salida">Salida</option>
                        <option value="ajuste">Ajuste</option>
                    </x-inputs.select>
                </x-inputs.group>

                <x-inputs.group class="w-full">
                    <x-inputs.number
                        name="quantity"
                        label="Cantidad"
                        wire:model="quantity"
                        min="1"
                        step="1"
                        placeholder="Cantidad"
                        required
                    ></x-inputs.number>
                    @error('quantity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </x-inputs.group>

                <x-inputs.group class="w-full">
                    <x-inputs.number
                        name="cost"
                        label="Costo (Opcional)"
                        wire:model="cost"
                        min="0"
                        step="0.01"
                        placeholder="Costo"
                    ></x-inputs.number>
                    @error('cost') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </x-inputs.group>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 flex justify-between">
            <button
                type="button"
                class="button"
                wire:click="closeModals"
            >
                <i class="mr-1 icon ion-md-close"></i>
                Cancelar
            </button>

            <button
                type="button"
                class="button button-primary"
                wire:click="saveTransaction"
            >
                <i class="mr-1 icon ion-md-save"></i>
                Guardar
            </button>
        </div>
    </x-modal>

    <!-- Modal para Ver Transacción -->
    <x-modal wire:model="showingViewModal">
        <div class="px-6 py-4">
            <div class="text-lg font-bold">Detalles de la Transacción</div>

            @if($inventoryMaterial)
            <div class="mt-5 space-y-4">
                <div>
                    <h5 class="font-medium text-gray-700">Material</h5>
                    <span>{{ $inventoryMaterial->material->name ?? '-' }}</span>
                </div>
                <div>
                    <h5 class="font-medium text-gray-700">Tipo</h5>
                    <span>
                        @if($inventoryMaterial->type === 'entrada')
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Entrada</span>
                        @elseif($inventoryMaterial->type === 'salida')
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Salida</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Ajuste</span>
                        @endif
                    </span>
                </div>
                <div>
                    <h5 class="font-medium text-gray-700">Cantidad</h5>
                    <span class="font-semibold {{ $inventoryMaterial->quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $inventoryMaterial->quantity > 0 ? '+' : '' }}{{ $inventoryMaterial->quantity }}
                    </span>
                </div>
                <div>
                    <h5 class="font-medium text-gray-700">Costo</h5>
                    <span>{{ $inventoryMaterial->cost ? '$' . number_format($inventoryMaterial->cost, 2) : '-' }}</span>
                </div>
                <div>
                    <h5 class="font-medium text-gray-700">Usuario</h5>
                    <span>{{ $inventoryMaterial->user->name ?? '-' }}</span>
                </div>
                <div>
                    <h5 class="font-medium text-gray-700">Fecha</h5>
                    <span>{{ $inventoryMaterial->created_at->format('d/m/Y H:i:s') }}</span>
                </div>
            </div>
            @endif
        </div>

        <div class="px-6 py-4 bg-gray-50 flex justify-end">
            <button
                type="button"
                class="button"
                wire:click="$toggle('showingViewModal')"
            >
                <i class="mr-1 icon ion-md-close"></i>
                Cerrar
            </button>
        </div>
    </x-modal>

    <!-- Modal para Crear Material -->
    <x-modal wire:model="showingMaterialModal">
        <div class="px-6 py-4 max-h-[90vh] overflow-y-auto">
            <div class="text-lg font-bold">Nuevo Material</div>

            <div class="mt-5">
                <x-inputs.group class="w-full">
                    <x-inputs.text
                        name="materialName"
                        label="Nombre"
                        wire:model="materialName"
                        maxlength="255"
                        placeholder="Nombre"
                        required
                    ></x-inputs.text>
                    @error('materialName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </x-inputs.group>

                <x-inputs.group class="w-full">
                    <x-inputs.textarea
                        name="materialDescription"
                        label="Descripción"
                        wire:model="materialDescription"
                        placeholder="Descripción"
                    ></x-inputs.textarea>
                    @error('materialDescription') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </x-inputs.group>

                <x-inputs.group class="w-full">
                    <x-inputs.select
                        name="materialFarmId"
                        label="Finca"
                        wire:model="materialFarmId"
                        required
                    >
                        <option value="">Seleccione una Finca</option>
                        @foreach($farmsForSelect as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </x-inputs.select>
                    @error('materialFarmId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </x-inputs.group>

                <x-inputs.group class="w-full">
                    <div class="flex items-center justify-between mb-2">
                        <x-inputs.partials.label
                            name="materialMarketId"
                            label="Tienda"
                        ></x-inputs.partials.label>
                        @can('create', App\Models\Market::class)
                        <button
                            type="button"
                            wire:click="newMarket"
                            class="bg-green-600 text-white px-2 py-1 rounded-md text-sm hover:bg-green-700"
                            title="Nueva Tienda"
                        >
                            <i class="mr-1 icon ion-md-add text-white"></i>
                            Agregar
                        </button>
                        @endcan
                    </div>
                    <x-inputs.select
                        name="materialMarketId"
                        wire:model="materialMarketId"
                        required
                    >
                        <option value="">Seleccione una Tienda</option>
                        @foreach($marketsForSelect as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </x-inputs.select>
                    @error('materialMarketId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </x-inputs.group>

                <x-inputs.group class="w-full">
                    <x-inputs.checkbox
                        name="materialStatus"
                        label="Activo"
                        wire:model="materialStatus"
                    ></x-inputs.checkbox>
                    @error('materialStatus') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </x-inputs.group>

                <x-inputs.group class="w-full">
                    <div
                        x-data="imageViewerWithCompression('')"
                    >
                        <x-inputs.partials.label
                            name="materialImage"
                            label="Imagen"
                        ></x-inputs.partials.label>
                        <br />

                        <template x-if="imageUrl">
                            <img
                                :src="imageUrl"
                                class="object-cover rounded border border-gray-200"
                                style="width: 100px; height: 100px;"
                            />
                        </template>

                        <template x-if="!imageUrl">
                            <div
                                class="border rounded border-gray-200 bg-gray-100"
                                style="width: 100px; height: 100px;"
                            ></div>
                        </template>

                        <div class="mt-2">
                            <input
                                type="file"
                                name="materialImage"
                                id="materialImage"
                                accept="image/*"
                                x-ref="fileInput"
                                @change="compressAndUpload($event)"
                            />
                        </div>
                        <p class="text-xs text-gray-500 mt-1">La imagen se comprimirá automáticamente (máx. 1200px, calidad 75%)</p>
                        @error('materialImage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </x-inputs.group>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 flex justify-between">
            <button
                type="button"
                class="button"
                wire:click="closeModals"
            >
                <i class="mr-1 icon ion-md-close"></i>
                Cancelar
            </button>

            <button
                type="button"
                class="button button-primary"
                wire:click="saveMaterial"
            >
                <i class="mr-1 icon ion-md-save"></i>
                Guardar
            </button>
        </div>
    </x-modal>

    <!-- Modal para Nueva Tienda -->
    <x-modal wire:model="showingMarketModal">
        <div class="px-6 py-4">
            <div class="text-lg font-bold">Nueva Tienda</div>

            <div class="mt-5">
                <x-inputs.group class="w-full">
                    <x-inputs.text
                        name="newMarketName"
                        label="Nombre de la Tienda"
                        wire:model="newMarketName"
                        maxlength="255"
                        placeholder="Nombre"
                        required
                    ></x-inputs.text>
                    @error('newMarketName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </x-inputs.group>

                <x-inputs.group class="w-full">
                    <x-inputs.text
                        name="newMarketPhone"
                        label="Teléfono"
                        wire:model="newMarketPhone"
                        maxlength="255"
                        placeholder="Teléfono"
                    ></x-inputs.text>
                    @error('newMarketPhone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </x-inputs.group>

                <x-inputs.group class="w-full">
                    <x-inputs.text
                        name="newMarketDirection"
                        label="Dirección"
                        wire:model="newMarketDirection"
                        maxlength="255"
                        placeholder="Dirección"
                    ></x-inputs.text>
                    @error('newMarketDirection') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </x-inputs.group>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 flex justify-between">
            <button
                type="button"
                class="button"
                wire:click="$toggle('showingMarketModal')"
            >
                <i class="mr-1 icon ion-md-close"></i>
                Cancelar
            </button>

            <button
                type="button"
                class="button button-primary"
                wire:click="saveMarket"
            >
                <i class="mr-1 icon ion-md-save"></i>
                Guardar
            </button>
        </div>
    </x-modal>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('imageViewerWithCompression', (src = '') => {
            return {
                imageUrl: src,
                isCompressing: false,

                refreshUrl() {
                    this.imageUrl = this.$el.getAttribute("image-url")
                },

                async compressAndUpload(event) {
                    if (!event.target.files.length) return;
                    
                    const originalFile = event.target.files[0];
                    this.isCompressing = true;
                    
                    try {
                        // Comprimir la imagen
                        const compressedFile = await this.compressImage(originalFile, 1200, 1200, 0.75);
                        
                        // Mostrar preview de la imagen comprimida
                        const reader = new FileReader();
                        reader.readAsDataURL(compressedFile);
                        reader.onload = (e) => {
                            this.imageUrl = e.target.result;
                        };
                        
                        // Subir directamente a Livewire usando el método upload
                        @this.upload('materialImage', compressedFile, (uploadedFilename) => {
                            // Upload successful
                            this.isCompressing = false;
                            console.log('Imagen comprimida y subida exitosamente');
                        }, () => {
                            // Upload error
                            this.isCompressing = false;
                            alert('Error al subir la imagen comprimida.');
                            event.target.value = '';
                        }, (event) => {
                            // Upload progress (opcional, mostrar progreso)
                            if (event.detail.progress) {
                                console.log('Progreso:', event.detail.progress + '%');
                            }
                        });
                        
                    } catch (error) {
                        console.error('Error comprimiendo imagen:', error);
                        this.isCompressing = false;
                        alert('Error al comprimir la imagen. Intenta con otra imagen.');
                        event.target.value = '';
                    }
                },

                async compressImage(file, maxWidth, maxHeight, quality) {
                    return new Promise((resolve, reject) => {
                        const reader = new FileReader();
                        reader.readAsDataURL(file);
                        reader.onload = (e) => {
                            const img = new Image();
                            img.src = e.target.result;
                            
                            img.onload = () => {
                                const canvas = document.createElement('canvas');
                                let width = img.width;
                                let height = img.height;
                                
                                // Calcular nuevas dimensiones manteniendo aspect ratio
                                if (width > maxWidth || height > maxHeight) {
                                    const ratio = Math.min(maxWidth / width, maxHeight / height);
                                    width = width * ratio;
                                    height = height * ratio;
                                }
                                
                                canvas.width = width;
                                canvas.height = height;
                                
                                const ctx = canvas.getContext('2d');
                                ctx.drawImage(img, 0, 0, width, height);
                                
                                // Convertir a blob
                                canvas.toBlob((blob) => {
                                    if (!blob) {
                                        reject(new Error('Error al comprimir la imagen'));
                                        return;
                                    }
                                    
                                    // Crear un File desde el Blob
                                    const compressedFile = new File([blob], file.name, {
                                        type: 'image/jpeg',
                                        lastModified: Date.now()
                                    });
                                    
                                    resolve(compressedFile);
                                }, 'image/jpeg', quality);
                            };
                            
                            img.onerror = () => {
                                reject(new Error('Error al cargar la imagen'));
                            };
                        };
                        
                        reader.onerror = () => {
                            reject(new Error('Error al leer el archivo'));
                        };
                    });
                },

                fileChosen(event) {
                    this.compressAndUpload(event);
                },

                fileToDataUrl(event, callback) {
                    if (!event.target.files.length) return;

                    let file = event.target.files[0],
                        reader = new FileReader()

                    reader.readAsDataURL(file)
                    reader.onload = e => callback(e.target.result)
                },
            }
        });
    });
</script>
@endpush
