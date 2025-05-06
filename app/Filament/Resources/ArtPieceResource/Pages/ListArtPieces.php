<?php

namespace App\Filament\Resources\ArtPieceResource\Pages;

use App\Filament\Resources\ArtPieceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListArtPieces extends ListRecords
{
    protected static string $resource = ArtPieceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
