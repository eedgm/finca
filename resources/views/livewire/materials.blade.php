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
        x-init="initScanner()"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75"
    >
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">Escanear Código de Barras</h3>
                <button
                    @click="$wire.set('scanningBarcode', false); stopScanner()"
                    class="text-gray-500 hover:text-gray-700"
                >
                    <i class="icon ion-md-close text-2xl"></i>
                </button>
            </div>
            <div class="relative">
                <video id="scanner-video" class="w-full bg-black rounded overflow-hidden" style="min-height: 300px; display: none;"></video>
                <canvas id="scanner-canvas" class="w-full bg-black rounded overflow-hidden" style="min-height: 300px; display: none;"></canvas>
                <div id="scanner-placeholder" class="w-full bg-black rounded overflow-hidden flex items-center justify-center" style="min-height: 300px;">
                    <p class="text-white">Iniciando cámara...</p>
                </div>
            </div>
            <p class="mt-4 text-sm text-gray-600 text-center">
                <i class="icon ion-md-information-circle"></i> 
                Apunta la cámara al código de barras o captura una foto
            </p>
            <div class="mt-4 flex justify-center gap-2">
                <button
                    @click="captureAndProcess()"
                    class="button bg-green-600 hover:bg-green-700 text-white"
                >
                    <i class="icon ion-md-camera"></i> Capturar y Procesar
                </button>
                <button
                    @click="$wire.set('scanningBarcode', false); stopScanner()"
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
<script src="https://unpkg.com/@zxing/library@latest"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('barcodeScanner', () => {
            let codeReader = null;
            let stream = null;
            let video = null;
            let canvas = null;
            let isScanning = false;
            let scanInterval = null;
            
            return {
                async initScanner() {
                    video = document.getElementById('scanner-video');
                    canvas = document.getElementById('scanner-canvas');
                    const placeholder = document.getElementById('scanner-placeholder');
                    
                    try {
                        // Obtener acceso a la cámara
                        stream = await navigator.mediaDevices.getUserMedia({
                            video: {
                                facingMode: 'environment', // Cámara trasera
                                width: { ideal: 1280 },
                                height: { ideal: 720 }
                            }
                        });
                        
                        video.srcObject = stream;
                        video.setAttribute('playsinline', 'true');
                        await video.play();
                        
                        // Ocultar placeholder y mostrar video
                        placeholder.style.display = 'none';
                        video.style.display = 'block';
                        
                        // Inicializar ZXing para escaneo en tiempo real
                        codeReader = new ZXing.BrowserMultiFormatReader();
                        isScanning = true;
                        
                        // Escanear continuamente
                        this.scanContinuously();
                        
                    } catch (err) {
                        console.error('Error inicializando escáner:', err);
                        placeholder.innerHTML = '<p class="text-red-500">Error al acceder a la cámara. Usa "Capturar y Procesar" para tomar una foto.</p>';
                        
                        if (err.name === 'NotAllowedError') {
                            alert('Por favor, permite el acceso a la cámara en la configuración del navegador.');
                        } else if (err.name === 'NotFoundError') {
                            alert('No se encontró ninguna cámara disponible.');
                        }
                    }
                },
                
                async scanContinuously() {
                    if (!video || !isScanning || !codeReader) return;
                    
                    try {
                        // Usar decodeFromVideoDevice para escaneo continuo
                        codeReader.decodeFromVideoDevice(null, video, (result, error) => {
                            if (result) {
                                console.log('Código detectado:', result.text);
                                @this.set('materialCode', result.text);
                                this.stopScanner();
                                @this.set('scanningBarcode', false);
                            }
                            if (error) {
                                // Ignorar errores de escaneo continuo (NotFoundError es normal)
                                if (!error.message.includes('NotFoundException') && 
                                    !error.message.includes('No MultiFormat Readers')) {
                                    console.debug('Escaneo:', error.message);
                                }
                            }
                        });
                    } catch (err) {
                        console.error('Error en escaneo continuo:', err);
                    }
                },
                
                async captureAndProcess() {
                    if (!video || !stream) {
                        alert('La cámara no está disponible. Por favor, permite el acceso a la cámara.');
                        return;
                    }
                    
                    try {
                        // Ocultar video y mostrar canvas
                        video.style.display = 'none';
                        canvas.style.display = 'block';
                        
                        // Configurar canvas
                        const context = canvas.getContext('2d');
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;
                        
                        // Capturar frame del video
                        context.drawImage(video, 0, 0, canvas.width, canvas.height);
                        
                        // Convertir canvas a imagen
                        const imageData = canvas.toDataURL('image/png');
                        
                        // Procesar con ZXing para códigos de barras
                        const codeReader = new ZXing.BrowserMultiFormatReader();
                        const img = new Image();
                        img.src = imageData;
                        
                        img.onload = async () => {
                            try {
                                // Intentar detectar código de barras
                                const result = await codeReader.decodeFromImageElement(img);
                                
                                if (result && result.text) {
                                    console.log('Código detectado en imagen:', result.text);
                                    @this.set('materialCode', result.text);
                                    this.stopScanner();
                                    @this.set('scanningBarcode', false);
                                } else {
                                    alert('No se pudo detectar un código de barras en la imagen. Intenta de nuevo con mejor iluminación.');
                                    // Volver a mostrar video
                                    canvas.style.display = 'none';
                                    video.style.display = 'block';
                                }
                            } catch (decodeErr) {
                                console.error('Error procesando imagen:', decodeErr);
                                alert('No se pudo detectar un código de barras en la imagen. Asegúrate de que el código esté bien visible y enfocado.');
                                // Volver a mostrar video
                                canvas.style.display = 'none';
                                video.style.display = 'block';
                            }
                        };
                        
                        img.onerror = () => {
                            alert('Error al procesar la imagen. Intenta de nuevo.');
                            canvas.style.display = 'none';
                            video.style.display = 'block';
                        };
                        
                    } catch (err) {
                        console.error('Error capturando imagen:', err);
                        alert('Error al capturar la imagen. Intenta de nuevo.');
                        canvas.style.display = 'none';
                        video.style.display = 'block';
                    }
                },
                
                async stopScanner() {
                    isScanning = false;
                    
                    if (scanInterval) {
                        clearInterval(scanInterval);
                        scanInterval = null;
                    }
                    
                    if (codeReader) {
                        try {
                            codeReader.reset();
                        } catch (err) {
                            console.error('Error reseteando scanner:', err);
                        }
                        codeReader = null;
                    }
                    
                    if (stream) {
                        stream.getTracks().forEach(track => track.stop());
                        stream = null;
                    }
                    
                    if (video) {
                        video.srcObject = null;
                        video.pause();
                    }
                    
                    // Ocultar elementos
                    if (video) video.style.display = 'none';
                    if (canvas) canvas.style.display = 'none';
                }
            };
        });
    });
</script>
@endpush
