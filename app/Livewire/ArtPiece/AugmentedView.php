<?php

namespace App\Livewire\ArtPiece;

use App\Models\ArtPiece;
use Livewire\Component;

class AugmentedView extends Component
{
    public $artPiece;

    public function mount($id)
    {
        $this->artPiece = ArtPiece::find($id);
    }   

    public function render()
    {
        return view('livewire.art-piece.augmented-view');
    }
}
