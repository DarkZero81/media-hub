<?php

use App\Http\Controllers\MediaController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Guest Routes (Accessible to Everyone)
|--------------------------------------------------------------------------
*/

// Homepage feed showcasing files with dynamic filtering and search parameters
Route::get('/', [MediaController::class, 'index'])->name('media.index');

// Multi-media asset creation workbench (Kept above ID definitions to stop 404 errors)
Route::get('/media/create', [MediaController::class, 'create'])->name('media.create');

// Media display page rendering specific asset records and active user comments
Route::get('/media/{id}', [MediaController::class, 'show'])->name('media.show');

// Incremental media download path processing storage payloads and database metrics
Route::get('/media/{id}/download', [MediaController::class, 'download'])->name('media.download');


/*
|--------------------------------------------------------------------------
| Authentication Foundations (Laravel Breeze System Files)
|--------------------------------------------------------------------------
| Loaded right here so our custom route configurations always take absolute 
| priority over Breeze's implicit baseline index mappings.
*/
require __DIR__.'/auth.php';


/*
|--------------------------------------------------------------------------
| Protected User Ecosystem (Session-Authenticated Accounts Only)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Explicit workspace landing routing (Overrides default Breeze templates completely)
    Route::get('/dashboard', [MediaController::class, 'dashboard'])->name('media.dashboard');

    // New backend pipeline for immediate client data and avatar image updating
    Route::post('/dashboard/profile/update', [MediaController::class, 'updateProfile'])->name('profile.custom.update');

    // Multi-media post staging processing payload scripts
    Route::post('/media', [MediaController::class, 'store'])->name('media.store');

    // Direct CRUD record modification interface rendering
    Route::get('/media/{id}/edit', [MediaController::class, 'edit'])->name('media.edit');

    // Post update request handler updates text fields and handles active media swapping
    Route::post('/media/{id}/update', [MediaController::class, 'update'])->name('media.update');

    // Destructive delete operations removing physics media assets and pivot dependencies
    Route::post('/media/{id}/delete', [MediaController::class, 'destroy'])->name('media.destroy');

    // Target interaction metrics logging a user comment record to specific media items
    Route::post('/media/{id}/comment', [MediaController::class, 'storeComment'])->name('comments.store');

    // Many-to-many relationship handler to toggle an item on/off user bookmark lists
    Route::post('/media/{id}/like', [MediaController::class, 'toggleLike'])->name('media.like');

    /* --- Core Breeze Default Profile Sub-Routes --- */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
