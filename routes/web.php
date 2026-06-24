<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\EnsiklopediaController;
use App\Http\Controllers\DeteksiController;
use App\Http\Controllers\PakarController;
use App\Http\Controllers\AdminController;

// --- PUBLIC AUTH ROUTES ---
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- AUTHENTICATED ROUTES ---
Route::middleware(['auth'])->group(function () {
    
    // Shared access for User (Petani), Pakar, and Admin
    Route::middleware(['role:user,pakar,admin'])->group(function () {
        Route::get('/', [BerandaController::class, 'index'])->name('beranda');
        
        // Ensiklopedia
        Route::get('/ensiklopedia', [EnsiklopediaController::class, 'index'])->name('ensiklopedia.index');
        Route::get('/ensiklopedia/{slug}', [EnsiklopediaController::class, 'show'])->name('ensiklopedia.show');
        
        // Deteksi
        Route::get('/deteksi', [DeteksiController::class, 'index'])->name('deteksi.index');
        Route::get('/deteksi/start', [DeteksiController::class, 'start'])->name('deteksi.start');
        Route::post('/deteksi/start', [DeteksiController::class, 'start']);
        Route::get('/deteksi/wizard', [DeteksiController::class, 'wizard'])->name('deteksi.wizard');
        Route::post('/deteksi/wizard', [DeteksiController::class, 'processStep'])->name('deteksi.wizard.process');
        Route::get('/deteksi/next-symptoms', [DeteksiController::class, 'nextSymptoms'])->name('deteksi.next-symptoms');
        Route::get('/deteksi/result', [DeteksiController::class, 'result'])->name('deteksi.result');
        Route::post('/deteksi/reset', [DeteksiController::class, 'reset'])->name('deteksi.reset');
        Route::get('/riwayat', [DeteksiController::class, 'history'])->name('deteksi.history');
    });

    // Pakar & Admin Access
    Route::middleware(['role:pakar,admin'])->group(function () {
        Route::get('/pakar', [PakarController::class, 'dashboard'])->name('pakar.dashboard');
        
        Route::get('/pakar/rules/create', [PakarController::class, 'showInputRules'])->name('pakar.rules.create');
        Route::post('/pakar/rules', [PakarController::class, 'storeRules'])->name('pakar.rules.store');
        
        Route::get('/pakar/library/create', [PakarController::class, 'showInputLibrary'])->name('pakar.library.create');
        Route::post('/pakar/library', [PakarController::class, 'storeLibrary'])->name('pakar.library.store');
        
    });

    // Admin Access Only
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // CRUD Users
        Route::get('/users', [AdminController::class, 'indexUsers'])->name('users.index');
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::post('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
        
        // CRUD Rules
        Route::get('/rules', [AdminController::class, 'indexRules'])->name('rules.index');
        Route::get('/rules/create', [AdminController::class, 'createRule'])->name('rules.create');
        Route::post('/rules', [AdminController::class, 'storeRule'])->name('rules.store');
        Route::get('/rules/{id}/edit', [AdminController::class, 'editRule'])->name('rules.edit');
        Route::post('/rules/{id}', [AdminController::class, 'updateRule'])->name('rules.update');
        Route::post('/rules/{id}/delete', [AdminController::class, 'deleteRule'])->name('rules.delete');
        
        // CRUD Library
        Route::get('/library', [AdminController::class, 'indexLibrary'])->name('library.index');
        Route::get('/library/create', [AdminController::class, 'createLibrary'])->name('library.create');
        Route::post('/library', [AdminController::class, 'storeLibrary'])->name('library.store');
        Route::get('/library/{id}/edit', [AdminController::class, 'editLibrary'])->name('library.edit');
        Route::post('/library/{id}', [AdminController::class, 'updateLibrary'])->name('library.update');
        Route::post('/library/{id}/delete', [AdminController::class, 'deleteLibrary'])->name('library.delete');
    });
});