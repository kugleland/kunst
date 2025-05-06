<div>
    <h1>Augmented View</h1>
    <p>{{ $artPiece->title }}</p>
    <p>{{ $artPiece->height }} x {{ $artPiece->width }}</p>
    @php
        $mediaItems = $artPiece->getMedia('art_pieces');
        $publicFullUrl = isset($mediaItems[0])
            ? $mediaItems[0]->getFullUrl()
            : 'https://placehold.co/600x400?text=' . $artPiece->title . '';

    @endphp

    <div class="w-full h-92 p-3 grid grid-cols-2 gap-3">

        <div class="col-span-1">
            <div class="w-full h-full border-2 bg-neutral-300 border-neutral-900 p-3">
                <img src="{{ $publicFullUrl }}" alt="{{ $artPiece->title }}" class="w-full h-92 object-contain">
            </div>
        </div>

        <div class="col-span-1 bg-neutral-900">
            <model-viewer id="artViewer" ar ar-placement="wall" camera-controls touch-action="pan-y" orientation="0 1.5 0"
                ar-scale="fixed" xr-environment src="{{ asset('storage/models/simple-plane.glb') }}"
                alt="{{ $artPiece->title }}" scale="{{ $artPiece->width / 100 }} {{ $artPiece->height / 100 }} 1"
                class="w-full h-full relative">
                <div class="controls absolute bottom-0 right-0 bg-neutral-500 p-1">
                    <button slot="ar-button" class="ar-button">
                        View in your space
                    </button>
                </div>
            </model-viewer>
            <script type="module">
                const modelViewerTexture1 = document.querySelector("model-viewer#artViewer");

                modelViewerTexture1.addEventListener("load", () => {

                    const material = modelViewerTexture1.model.materials[0];

                    const createAndApplyTexture = async (channel, event) => {
                        const texture = await modelViewerTexture1.createTexture("{{ $publicFullUrl }}");
                        if (channel.includes('base') || channel.includes('metallic')) {
                            material.pbrMetallicRoughness[channel].setTexture(texture);
                        } else {
                            material[channel].setTexture(texture);
                        }
                    }

                    setTimeout(() => {
                        createAndApplyTexture('baseColorTexture', event);
                    }, 300);
                });
            </script>
        </div>
    </div>
</div>

{{-- @push('scripts')
    @vite('resources/js/model-viewer-texture.js')
@endpush --}}
