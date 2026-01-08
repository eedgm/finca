<div>
    <div class="mb-5 mt-4 p-4 bg-black text-white">
        <div class="flex flex-wrap justify-between">
            <div class="md:w-1/2">
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
            <div class="md:w-1/2 text-right">
                @can('create', App\Models\Material::class)
                <button
                    wire:click="newMaterial"
                    class="button button-primary"
                >
                    <i class="mr-1 icon ion-md-add"></i>
                    @lang('crud.common.create')
                </button>
                @endcan
            </div>
        </div>
    </div>

    <div class="block w-full overflow-auto scrolling-touch text-white bg-black">
        <table class="w-full max-w-full mb-4 bg-transparent">
            <thead class="text-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left">
                        Estado
                    </th>
                    <th class="px-4 py-3 text-left">
                        Nombre
                    </th>
                    <th class="px-4 py-3 text-left">
                        Descripción
                    </th>
                    <th class="px-4 py-3 text-left">
                        Finca
                    </th>
                    <th class="px-4 py-3 text-left">
                        Tienda
                    </th>
                    <th class="px-4 py-3 text-left">
                        Imagen
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="text-gray-600">
                @forelse($materials as $material)
                <tr class="hover:bg-gray-700 odd:bg-gray-900 even:bg-gray-800 text-white">
                    <td class="px-4 py-3 text-left">
                        @if($material->status)
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                Activo
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                Inactivo
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-left">
                        {{ $material->name ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-left">
                        {{ $material->description ? \Illuminate\Support\Str::limit($material->description, 50) : '-' }}
                    </td>
                    <td class="px-4 py-3 text-left">
                        {{ optional($material->farm)->name ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-left">
                        {{ optional($material->market)->name ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-left">
                        @if($material->image)
                            <x-partials.thumbnail
                                src="{{ \Storage::url($material->image) }}"
                            />
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center" style="width: 134px;">
                        <div role="group" aria-label="Row Actions" class="relative inline-flex align-middle">
                            @can('view', $material)
                            <button
                                wire:click="viewMaterial({{ $material->id }})"
                                class="mr-1 button"
                                title="Ver"
                            >
                                <i class="icon ion-md-eye"></i>
                            </button>
                            @endcan
                            @can('update', $material)
                            <button
                                wire:click="editMaterial({{ $material->id }})"
                                class="mr-1 button"
                                title="Editar"
                            >
                                <i class="icon ion-md-create"></i>
                            </button>
                            @endcan
                            @can('delete', $material)
                            <button
                                wire:click="deleteMaterial({{ $material->id }})"
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
                    <td colspan="7">
                        @lang('crud.common.no_items_found')
                    </td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="7">
                        <div class="mt-10 px-4">
                            {{ $materials->links() }}
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Modal para Crear/Editar Material -->
    <x-modal wire:model="showingModal">
        <div class="px-6 py-4 max-h-[90vh] overflow-y-auto">
            <div class="text-lg font-bold">{{ $modalTitle }}</div>

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
                    <div class="flex items-center gap-2">
                        <div class="flex-1">
                            <x-inputs.text
                                name="materialCode"
                                label="Código"
                                wire:model="materialCode"
                                maxlength="255"
                                placeholder="Código o escanear código de barras"
                                id="materialCodeInput"
                            ></x-inputs.text>
                        </div>
                        <div class="mt-6">
                            <button
                                type="button"
                                wire:click="$set('scanningBarcode', true)"
                                class="button bg-blue-600 hover:bg-blue-700 text-white"
                                title="Escanear código de barras"
                            >
                                <i class="icon ion-md-barcode"></i>
                            </button>
                        </div>
                    </div>
                    @error('materialCode') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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
                        x-data="imageViewer('{{ $editing && $material && $material->image ? \Storage::url($material->image) : '' }}')"
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
                                wire:model="materialImage"
                                @change="fileChosen"
                            />
                        </div>
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

    <!-- Modal para Ver Material -->
    <x-modal wire:model="showingViewModal">
        <div class="px-6 py-4">
            <div class="text-lg font-bold">Detalles del Material</div>

            @if($material)
            <div class="mt-5 space-y-4">
                <div>
                    <h5 class="font-medium text-gray-700">Nombre</h5>
                    <span>{{ $material->name ?? '-' }}</span>
                </div>
                <div>
                    <h5 class="font-medium text-gray-700">Descripción</h5>
                    <span>{{ $material->description ?? '-' }}</span>
                </div>
                <div>
                    <h5 class="font-medium text-gray-700">Finca</h5>
                    <span>{{ $material->farm->name ?? '-' }}</span>
                </div>
                <div>
                    <h5 class="font-medium text-gray-700">Tienda</h5>
                    <span>{{ $material->market->name ?? '-' }}</span>
                </div>
                <div>
                    <h5 class="font-medium text-gray-700">Estado</h5>
                    <span>
                        @if($material->status)
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Activo</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Inactivo</span>
                        @endif
                    </span>
                </div>
                <div>
                    <h5 class="font-medium text-gray-700">Imagen</h5>
                    @if($material->image)
                        <x-partials.thumbnail
                            src="{{ \Storage::url($material->image) }}"
                            size="150"
                        />
                    @else
                        <span class="text-gray-400">Sin imagen</span>
                    @endif
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

    <!-- Scanner Modal -->
    @if($scanningBarcode)
    <div
        x-data="barcodeScanner()"
        x-init="startScanning()"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75"
    >
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">Escanear Código de Barras</h3>
                <button
                    @click="$wire.set('scanningBarcode', false); stopScanning()"
                    class="text-gray-500 hover:text-gray-700"
                >
                    <i class="icon ion-md-close text-2xl"></i>
                </button>
            </div>
            <div id="barcode-scanner" class="w-full bg-black rounded overflow-hidden" style="min-height: 300px;"></div>
            <p class="mt-4 text-sm text-gray-600 text-center">Apunta la cámara al código de barras</p>
            <div class="mt-4 flex justify-center">
                <button
                    @click="$wire.set('scanningBarcode', false); stopScanning()"
                    class="button"
                >
                    Cancelar
                </button>
            </div>
        </div>
    </div>
    @endif

</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('barcodeScanner', () => {
            let html5QrCode = null;
            let isScanning = false;
            
            return {
                async startScanning() {
                    if (isScanning) return;
                    
                    const scannerElement = document.getElementById('barcode-scanner');
                    if (!scannerElement) {
                        console.error('Elemento scanner no encontrado');
                        return;
                    }
                    
                    try {
                        // Limpiar cualquier instancia anterior
                        if (html5QrCode) {
                            try {
                                await html5QrCode.stop();
                                await html5QrCode.clear();
                            } catch (e) {
                                // Ignorar errores al limpiar
                            }
                        }
                        
                        // Crear nueva instancia
                        html5QrCode = new Html5Qrcode("barcode-scanner");
                        isScanning = true;
                        
                        // Configuración para códigos de barras
                        const config = {
                            fps: 10,
                            qrbox: function(viewfinderWidth, viewfinderHeight) {
                                let minEdgePercentage = 0.7;
                                let minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
                                let qrboxSize = Math.floor(minEdgeSize * minEdgePercentage);
                                return {
                                    width: qrboxSize,
                                    height: qrboxSize
                                };
                            },
                            aspectRatio: 1.0,
                            formatsToSupport: [
                                Html5QrcodeSupportedFormats.CODE_128,
                                Html5QrcodeSupportedFormats.CODE_39,
                                Html5QrcodeSupportedFormats.CODE_93,
                                Html5QrcodeSupportedFormats.EAN_13,
                                Html5QrcodeSupportedFormats.EAN_8,
                                Html5QrcodeSupportedFormats.UPC_A,
                                Html5QrcodeSupportedFormats.UPC_E,
                                Html5QrcodeSupportedFormats.CODABAR,
                                Html5QrcodeSupportedFormats.ITF
                            ]
                        };
                        
                        // Intentar obtener la cámara trasera primero
                        const cameras = await Html5Qrcode.getCameras();
                        let cameraId = null;
                        
                        // Buscar cámara trasera
                        for (let camera of cameras) {
                            if (camera.label.toLowerCase().includes('back') || 
                                camera.label.toLowerCase().includes('rear') ||
                                camera.label.toLowerCase().includes('environment')) {
                                cameraId = camera.id;
                                break;
                            }
                        }
                        
                        // Si no hay cámara trasera, usar la primera disponible
                        if (!cameraId && cameras.length > 0) {
                            cameraId = cameras[0].id;
                        }
                        
                        if (!cameraId) {
                            throw new Error('No se encontró ninguna cámara disponible');
                        }
                        
                        // Iniciar escaneo
                        await html5QrCode.start(
                            cameraId,
                            config,
                            (decodedText, decodedResult) => {
                                // Código detectado
                                console.log('Código detectado:', decodedText);
                                @this.set('materialCode', decodedText);
                                this.stopScanning();
                                @this.set('scanningBarcode', false);
                            },
                            (errorMessage) => {
                                // Ignorar errores de escaneo continuo (NotFoundError es normal)
                            }
                        );
                    } catch (err) {
                        console.error('Error inicializando escáner:', err);
                        isScanning = false;
                        html5QrCode = null;
                        
                        let errorMsg = 'Error al acceder a la cámara. ';
                        if (err.name === 'NotAllowedError') {
                            errorMsg += 'Por favor, permite el acceso a la cámara en la configuración del navegador.';
                        } else if (err.name === 'NotFoundError') {
                            errorMsg += 'No se encontró ninguna cámara disponible.';
                        } else {
                            errorMsg += err.message || 'Asegúrate de dar permisos de cámara.';
                        }
                        
                        alert(errorMsg);
                        @this.set('scanningBarcode', false);
                    }
                },
                
                async stopScanning() {
                    if (html5QrCode && isScanning) {
                        try {
                            await html5QrCode.stop();
                            await html5QrCode.clear();
                        } catch (err) {
                            console.error('Error deteniendo escáner:', err);
                        }
                        html5QrCode = null;
                        isScanning = false;
                    }
                }
            };
        });
    });
</script>
@endpush
