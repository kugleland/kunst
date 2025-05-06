<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\ArtPiece\IndexArtPieces;
use App\Livewire\ArtPiece\ShowArtPiece;
use App\Livewire\ArtPiece\AugmentedView;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('art-pieces', IndexArtPieces::class)->name('art-pieces.index');
Route::get('art-pieces/{id}', ShowArtPiece::class)->name('art-pieces.show');
Route::get('art-pieces/{id}/augmented', AugmentedView::class)->name('art-pieces.augmented');


Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
