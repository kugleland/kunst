<div>
    <h1>Art Pieces</h1>
    <div class="grid lg:grid-cols-3 xl:grid-cols-3 gap-4">
        @foreach ($artPieces as $artPiece)
            @php
                $mediaItems = $artPiece->getMedia('art_pieces');
                $publicFullUrl = isset($mediaItems[0])
                    ? $mediaItems[0]->getUrl('preview')
                    : 'https://placehold.co/600x400?text=' . $artPiece->title . '';

            @endphp
            <flux:card class="space-y-3">
                <div class="w-full h-64 bg-neutral-200 p-3">
                    <img src="{{ $publicFullUrl }}" alt="" class="w-full h-full object-contain">
                </div>

                <div class="space-y-1">
                    <flux:text>{{ $artPiece->title }}</flux:text>
                    <flux:text>{{ $artPiece->description }}</flux:text>
                    <flux:text>{{ $artPiece->price }}</flux:text>
                    <flux:text>{{ $artPiece->width }} x {{ $artPiece->height }}</flux:text>
                </div>

                <div>
                    <flux:button href="{{ route('art-pieces.show', $artPiece->id) }}">
                        View
                    </flux:button>
                </div>
            </flux:card>
        @endforeach
    </div>
</div>
