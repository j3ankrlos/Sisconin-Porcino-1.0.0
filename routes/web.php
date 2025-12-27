<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AnimalController;
use App\Http\Controllers\Admin\EmpresaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('admin.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/admin', [AdminController::class, 'index'])->middleware(['auth', 'verified'])->name('admin.index');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    Route::resource('users', AdminUserController::class);
    Route::resource('roles', RoleController::class);
    
    // Gestión de Animales
    Route::get('animals', [AnimalController::class, 'index'])->name('animals.index');
    Route::get('animals/create', [AnimalController::class, 'create'])->name('animals.create');

    // Configuración de Empresa y Granjas
    Route::get('empresa', [EmpresaController::class, 'index'])->name('empresa.index');
    Route::get('granjas', [EmpresaController::class, 'granjas'])->name('granjas.index');
    Route::get('especies', [EmpresaController::class, 'especies'])->name('especies.index');
    Route::get('razas', [EmpresaController::class, 'razas'])->name('razas.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
