<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Models\User;

Route::get('/test-assign-role', function () {
    $user = User::find(2);
    Log::info('Atribuindo papel admin ao usuário: ' . $user->email);
    $user->assignRole('admin');
    Log::info('Papel atribuído com sucesso: ' . $user->getRoleNames());
    return $user->getRoleNames();
});


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
