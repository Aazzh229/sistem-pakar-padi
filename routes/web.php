<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\EnsiklopediaController;
use App\Http\Controllers\DeteksiController;
use App\Http\Controllers\PakarController;
use App\Http\Controllers\AdminController;

// --- PUBLIC AUTH ROUTES ---
Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $user = Auth::user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if ($user->role === 'pakar') {
        return redirect()->route('pakar.dashboard');
    }

    return redirect()->route('beranda');
})->name('root');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/admin/search/gejala',[AdminController::class,'searchGejala']);
Route::get('/admin/search/diagnosa',[AdminController::class,'searchDiagnosa']);
Route::get('/admin/search/penyakit',[AdminController::class,'searchPenyakit']);
Route::get('/admin/search/hama',[AdminController::class,'searchHama']);

// --- AUTHENTICATED ROUTES ---
Route::middleware(['auth'])->group(function () {
    
    // Diagnosis history is shared, but admin receives the desktop admin layout in the view.
    Route::middleware(['role:user,pakar,admin'])->group(function () {
        Route::get('/riwayat', [DeteksiController::class, 'history'])->name('deteksi.history');
        Route::post('/riwayat/hapus', [DeteksiController::class, 'deleteHistory'])->name('deteksi.history.delete');
    });

    // Mobile app access for User (Petani) and Pakar only.
    Route::middleware(['role:user,pakar'])->group(function () {
        Route::get('/beranda', [BerandaController::class, 'index'])->name('beranda');
        
        // Ensiklopedia
        Route::get('/ensiklopedia', [EnsiklopediaController::class, 'index'])->name('ensiklopedia.index');
        Route::get('/ensiklopedia/suggestions', [EnsiklopediaController::class, 'suggestions'])->name('ensiklopedia.suggestions');
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
    });

    // Pakar Access
Route::middleware(['role:pakar'])->group(function () {

    Route::get('/pakar', [PakarController::class, 'dashboard'])
        ->name('pakar.dashboard');

    // Master
    Route::get('/pakar/master/create', [PakarController::class, 'showInputMaster'])
        ->name('pakar.master.create');
    Route::post('/pakar/master', [PakarController::class, 'storeMaster'])
        ->name('pakar.master.store');

    // Rules
    Route::get('/pakar/search/gejala', [PakarController::class, 'searchGejala'])
        ->name('pakar.search.gejala');
    Route::get('/pakar/search/penyakit', [PakarController::class, 'searchPenyakit'])
        ->name('pakar.search.penyakit');
    Route::get('/pakar/search/hama', [PakarController::class, 'searchHama'])
        ->name('pakar.search.hama');

    Route::get('/pakar/rules', [PakarController::class, 'indexRules'])
        ->name('pakar.rules.index');
    Route::get('/pakar/rules/create', [PakarController::class, 'showInputRules'])
        ->name('pakar.rules.create');
    Route::post('/pakar/rules', [PakarController::class, 'storeRules'])
        ->name('pakar.rules.store');
    Route::get('/pakar/rules/{id}/edit', [PakarController::class, 'editRule'])
        ->name('pakar.rules.edit');
    Route::post('/pakar/rules/{id}', [PakarController::class, 'updateRule'])
        ->name('pakar.rules.update');
    Route::post('/pakar/rules/{id}/delete', [PakarController::class, 'deleteRule'])
        ->name('pakar.rules.delete');

    // Library
    Route::get('/pakar/library', [PakarController::class, 'indexLibrary'])
        ->name('pakar.library.index');
    Route::get('/pakar/library/create', [PakarController::class, 'showInputLibrary'])
        ->name('pakar.library.create');
    Route::post('/pakar/library', [PakarController::class, 'storeLibrary'])
        ->name('pakar.library.store');
    Route::get('/pakar/library/{id}/edit', [PakarController::class, 'editLibrary'])
        ->name('pakar.library.edit');
    Route::post('/pakar/library/{id}', [PakarController::class, 'updateLibrary'])
        ->name('pakar.library.update');
    Route::post('/pakar/library/{id}/delete', [PakarController::class, 'deleteLibrary'])
        ->name('pakar.library.delete');

    // Input Gejala
    Route::get('/pakar/gejala/create', [PakarController::class, 'showInputMaster'])
        ->name('pakar.gejala.create');
    Route::post('/pakar/gejala', [PakarController::class, 'storeMaster'])
        ->name('pakar.gejala.store');

    // Input Penyakit
    Route::get('/pakar/penyakit/create', [PakarController::class, 'showInputMaster'])
        ->name('pakar.penyakit.create');
    Route::post('/pakar/penyakit', [PakarController::class, 'storeMaster'])
        ->name('pakar.penyakit.store');

    // Input Hama
    Route::get('/pakar/hama/create', [PakarController::class, 'showInputMaster'])
        ->name('pakar.hama.create');
    Route::post('/pakar/hama', [PakarController::class, 'storeMaster'])
        ->name('pakar.hama.store');
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
        Route::post('/users/{id}/toggle-status', [AdminController::class, 'toggleStatus'])->name('users.toggle-status');
        
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
