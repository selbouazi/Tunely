<?php

use App\Http\Controllers\ProfileController;
use App\Models\Category;
use App\Models\Instrument;
use App\Models\Order;
use App\Models\User;
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

Route::get('/faq', function () {
    return Inertia::render('FAQ');
})->name('faq');

Route::get('/aviso-legal', function () {
    return Inertia::render('AvisoLegal');
})->name('aviso-legal');

Route::get('/privacidad', function () {
    return Inertia::render('Privacidad');
})->name('privacidad');

Route::get('/condiciones', function () {
    return Inertia::render('Condiciones');
})->name('condiciones');

Route::get('/catalogo/{id}', function ($id) {
    $instrument = Instrument::with('category')->findOrFail($id);

    return Inertia::render('InstrumentoDetalle', [
        'instrument' => $instrument,
    ]);
})->name('instrumento.detalle');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Admin/Dashboard', [
            'totalProductos' => Instrument::count(),
            'totalPedidos' => Order::count(),
            'totalUsuarios' => User::count(),
            'totalCategorias' => Category::count(),
        ]);
    })->name('admin.dashboard');

    Route::get('/categorias', [\App\Http\Controllers\CategoryController::class, 'index'])->name('admin.categorias.index');
    Route::post('/categorias', [\App\Http\Controllers\CategoryController::class, 'store'])->name('admin.categorias.store');
    Route::put('/categorias/{category}', [\App\Http\Controllers\CategoryController::class, 'update'])->name('admin.categorias.update');
    Route::delete('/categorias/{category}', [\App\Http\Controllers\CategoryController::class, 'destroy'])->name('admin.categorias.destroy');

    Route::get('/productos', [\App\Http\Controllers\InstrumentController::class, 'index'])->name('admin.instrumentos.index');
    Route::get('/productos/crear', [\App\Http\Controllers\InstrumentController::class, 'create'])->name('admin.instrumentos.create');
    Route::post('/productos', [\App\Http\Controllers\InstrumentController::class, 'store'])->name('admin.instrumentos.store');
    Route::get('/productos/{instrument}/editar', [\App\Http\Controllers\InstrumentController::class, 'edit'])->name('admin.instrumentos.edit');
    Route::put('/productos/{instrument}', [\App\Http\Controllers\InstrumentController::class, 'update'])->name('admin.instrumentos.update');
    Route::delete('/productos/{instrument}', [\App\Http\Controllers\InstrumentController::class, 'destroy'])->name('admin.instrumentos.destroy');
    Route::patch('/productos/{instrument}/activar', [\App\Http\Controllers\InstrumentController::class, 'activate'])->name('admin.instrumentos.activate');
    Route::post('/productos/descuento', [\App\Http\Controllers\InstrumentController::class, 'bulkDiscount'])->name('admin.instrumentos.bulk-discount');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
