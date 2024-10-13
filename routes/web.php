<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RentalController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::redirect('/', '/login');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/car', [RentalController::class, 'index'])->name('index');
    Route::post('/addCar', [RentalController::class, 'carForm'])->name('carForm');
    Route::post('/addCar', [RentalController::class, 'addCar'])->name('addCar');
    Route::get('/rent-car/{id}', [RentalController::class, 'rentForm'])->name('rentForm');
    Route::post('/rent-car', [RentalController::class, 'rentCar'])->name('rentCar');
    Route::post('/return-car/{id}', [RentalController::class, 'returnForm'])->name('returnForm');
    Route::post('/return-car', [RentalController::class, 'returnCar'])->name('returnCar');
});

require __DIR__ . '/auth.php';
