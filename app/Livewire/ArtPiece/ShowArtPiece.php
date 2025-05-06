<?php

namespace App\Livewire\ArtPiece;

use App\Models\ArtPiece;
use Livewire\Component;

class ShowArtPiece extends Component
{
    public $artPiece;

    public function mount($id)
    {
        $this->artPiece = ArtPiece::find($id);
    }

    public function render()
    {
        return view('livewire.art-piece.show-art-piece');
    }
}
