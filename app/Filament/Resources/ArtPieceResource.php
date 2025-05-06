<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArtPieceResource\Pages;
use App\Filament\Resources\ArtPieceResource\RelationManagers;
use App\Models\ArtPiece;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
class ArtPieceResource extends Resource
{
    protected static ?string $model = ArtPiece::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title'),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                    ->collection('art_pieces'),
                Forms\Components\TextInput::make('medium'),
                Forms\Components\TextInput::make('height'),
                Forms\Components\TextInput::make('width'),
                Forms\Components\TextInput::make('depth'),
                Forms\Components\TextInput::make('year'),
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\TextInput::make('artist_id')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                SpatieMediaLibraryImageColumn::make('image')
                    ->collection('art_pieces'),
                Tables\Columns\TextColumn::make('medium')
                    ->searchable(),
                Tables\Columns\TextColumn::make('height')
                    ->searchable(),
                Tables\Columns\TextColumn::make('width')
                    ->searchable(),
                Tables\Columns\TextColumn::make('depth')
                    ->searchable(),
                Tables\Columns\TextColumn::make('year')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('artist_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArtPieces::route('/'),
            'create' => Pages\CreateArtPiece::route('/create'),
            'edit' => Pages\EditArtPiece::route('/{record}/edit'),
        ];
    }
}
