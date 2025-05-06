<div>
    <h1>{{ $artPiece->title }}</h1>

    <div>
        @php
            $mediaItems = $artPiece->getMedia('art_pieces');
            $publicFullUrl = isset($mediaItems[0])
                ? $mediaItems[0]->getUrl('preview')
                : 'https://placehold.co/600x400?text=' . $artPiece->title . '';

        @endphp

        <img src="{{ $publicFullUrl }}" alt="" class="w-full h-64 object-contain">
    </div>

    <div>
        <flux:button href="{{ route('art-pieces.augmented', $artPiece->id) }}">
            Augmented View
        </flux:button>
    </div>
</div>
