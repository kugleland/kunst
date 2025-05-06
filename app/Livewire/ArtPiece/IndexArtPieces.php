<?php

namespace App\Livewire\ArtPiece;

use App\Models\ArtPiece;
use Livewire\Component;

class IndexArtPieces extends Component
{
    public $artPieces;

    public function mount()
    {
        $this->artPieces = ArtPiece::all();
    }

    public function render()
    {
        return view('livewire.art-piece.index-art-pieces');
    }
}
