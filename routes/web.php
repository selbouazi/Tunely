<?php

use App\Http\Controllers\ProfileController;
use App\Models\Category;
use App\Models\Instrument;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    $instruments = Instrument::with('category')->where('disponible', true)->take(4)->get();

    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'instruments' => $instruments,
    ]);
})->name('home');

Route::get('/catalogo', function () {
    $instruments = Instrument::with('category')->where('disponible', true)->get();
    $categories = Category::all();

    return Inertia::render('Catalogo', [
        'instruments' => $instruments,
        'categories' => $categories,
    ]);
})->name('catalogo');

Route::get('/quien-somos', function () {
    return Inertia::render('QuienSomos');
})->name('quien-somos');

Route::get('/contacto', function () {
    return Inertia::render('Contacto');
})->name('contacto');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
