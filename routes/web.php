<?php

use App\Http\Controllers\ProfileController;
use App\Models\Category;
use App\Models\Instrument;
use App\Models\Order;
use App\Models\PendingComment;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    $instruments = Instrument::with('category')->where('disponible', true)->where('stock', '>', 0)->take(4)->get();

    $carouselDir = public_path('img/carrusel');
    $carouselItems = [];
    $titles = [
        ['title' => 'Tu tienda de instrumentos musicales', 'subtitle' => 'Instrumentos nuevos y de segunda mano'],
        ['title' => 'Los mejores precios', 'subtitle' => 'Calidad garantizada en cada instrumento'],
        ['title' => 'Valoraciones reales', 'subtitle' => 'Miles de clientes satisfechos'],
        ['title' => 'Envío rápido y seguro', 'subtitle' => 'Recibe tu instrumento en 24-48h'],
        ['title' => 'Atención personalizada', 'subtitle' => 'Te ayudamos a encontrar lo que buscas'],
    ];

    $files = collect(scandir($carouselDir))
        ->filter(fn($f) => preg_match('/^\d+\.webp$/', $f))
        ->sort(SORT_NUMERIC)
        ->values();

    foreach ($files as $i => $file) {
        $carouselItems[] = [
            'image' => "/img/carrusel/{$file}",
            'title' => $titles[$i % count($titles)]['title'],
            'subtitle' => $titles[$i % count($titles)]['subtitle'],
            'cta' => 'Ver Catálogo',
            'link' => '/catalogo',
        ];
    }

    $topRated = Instrument::withAvg('ratings', 'rating')
        ->withCount('ratings')
        ->where('disponible', true)
        ->where('stock', '>', 0)
        ->having('ratings_avg_rating', '>', 0)
        ->orderByDesc('ratings_avg_rating')
        ->take(4)
        ->get();

    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'instruments' => $instruments,
        'carouselItems' => $carouselItems,
        'topRated' => $topRated,
    ]);
})->name('home');

Route::get('/catalogo', function () {
    $instruments = Instrument::with('category')->where('disponible', true)->where('stock', '>', 0)->get();
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
    return Inertia::render('Contacto', [
        'contactInfo' => \App\Models\ContactInfo::orderBy('order')->get(),
    ]);
})->name('contacto');

Route::post('/contacto', \App\Http\Controllers\ContactController::class)->name('contacto.store');

Route::get('/faq', function () {
    return Inertia::render('FAQ', [
        'faqs' => \App\Models\Faq::where('active', true)->orderBy('order')->get(),
    ]);
})->name('faq');

Route::get('/aviso-legal', function () {
    return Inertia::render('AvisoLegal', [
        'contactInfo' => \App\Models\ContactInfo::orderBy('order')->get(),
    ]);
})->name('aviso-legal');

Route::get('/privacidad', function () {
    return Inertia::render('Privacidad', [
        'contactInfo' => \App\Models\ContactInfo::orderBy('order')->get(),
    ]);
})->name('privacidad');

Route::get('/condiciones', function () {
    return Inertia::render('Condiciones');
})->name('condiciones');

Route::get('/catalogo/{id}', function ($id) {
    $instrument = Instrument::with('category', 'ratings.user')->findOrFail($id);

    $userRating = null;
    $canRate = false;
    if (auth()->check()) {
        $userRating = Rating::where('user_id', auth()->id())
            ->where('instrument_id', $instrument->id)
            ->first();

        $canRate = PendingComment::where('user_id', auth()->id())
            ->where('instrument_id', $instrument->id)
            ->where('has_commented', false)
            ->exists();
    }

    return Inertia::render('InstrumentoDetalle', [
        'instrument' => $instrument,
        'userRating' => $userRating,
        'canRate' => $canRate,
    ]);
})->name('instrumento.detalle');

Route::post('/catalogo/{instrument}/rating', [\App\Http\Controllers\RatingController::class, 'store'])
    ->middleware('auth')
    ->name('instrumento.rating.store');

Route::post('/catalogo/{instrument}/consulta', function (Request $request, \App\Models\Instrument $instrument) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'message' => 'required|string|max:150',
    ]);

    \App\Models\ContactMessage::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'subject' => 'Consulta sobre producto: ' . $instrument->marca . ' ' . $instrument->modelo . ' (#' . $instrument->id . ')',
        'message' => $validated['message'],
    ]);

    return redirect()->back()->with('success', 'Consulta enviada correctamente. Te responderemos pronto.');
})->name('instrumento.consulta');

Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect('/admin/dashboard');
    }

    $orders = auth()->user()->orders()
        ->with('items.instrument')
        ->orderByDesc('created_at')
        ->get();

    return Inertia::render('Dashboard', [
        'orders' => $orders,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/mis-valoraciones', function () {
    $ratings = auth()->user()->ratings()
        ->with('instrument')
        ->orderByDesc('created_at')
        ->get();

    $pendingRatings = \App\Models\PendingComment::with('instrument')
        ->where('user_id', auth()->id())
        ->where('has_commented', false)
        ->get()
        ->map(fn($p) => [
            'id' => $p->id,
            'instrument_id' => $p->instrument_id,
            'instrument_name' => $p->instrument->marca . ' ' . $p->instrument->modelo,
            'instrument' => $p->instrument,
        ]);

    return Inertia::render('MisValoraciones', [
        'ratings' => $ratings,
        'pendingRatings' => $pendingRatings,
    ]);
})->middleware(['auth', 'verified'])->name('mis-valoraciones');

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        $topProducts = \App\Models\OrderItem::selectRaw('instrument_id, SUM(cantidad) as total_vendido')
            ->whereHas('order', fn($q) => $q->where('estado', '!=', 'cancelado'))
            ->groupBy('instrument_id')
            ->orderByDesc('total_vendido')
            ->take(10)
            ->with('instrument')
            ->get()
            ->map(fn($item) => [
                'label' => $item->instrument->marca . ' ' . $item->instrument->modelo,
                'value' => (int) $item->total_vendido,
            ]);

        return Inertia::render('Admin/Dashboard', [
            'totalProductos' => Instrument::count(),
            'totalPedidos' => Order::count(),
            'totalUsuarios' => User::count(),
            'totalCategorias' => Category::count(),
            'topProducts' => $topProducts,
            'discountActive' => Instrument::where('descuento_general_applied', true)->exists(),
            'discountAffected' => Instrument::where('descuento_general_applied', true)->count(),
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

    Route::get('/subcategorias', [\App\Http\Controllers\SubcategoryController::class, 'index'])->name('admin.subcategorias.index');
    Route::post('/subcategorias', [\App\Http\Controllers\SubcategoryController::class, 'store'])->name('admin.subcategorias.store');
    Route::put('/subcategorias/{subcategory}', [\App\Http\Controllers\SubcategoryController::class, 'update'])->name('admin.subcategorias.update');
    Route::delete('/subcategorias/{subcategory}', [\App\Http\Controllers\SubcategoryController::class, 'destroy'])->name('admin.subcategorias.destroy');

    Route::get('/pedidos', [\App\Http\Controllers\OrderController::class, 'index'])->name('admin.pedidos.index');
    Route::get('/pedidos/{order}', [\App\Http\Controllers\OrderController::class, 'show'])->name('admin.pedidos.show');
    Route::patch('/pedidos/{order}/estado', [\App\Http\Controllers\OrderController::class, 'updateStatus'])->name('admin.pedidos.update-status');
    Route::delete('/pedidos/{order}', [\App\Http\Controllers\OrderController::class, 'destroy'])->name('admin.pedidos.destroy');

    Route::get('/opiniones', [\App\Http\Controllers\RatingController::class, 'adminIndex'])->name('admin.opiniones.index');
    Route::delete('/opiniones/{rating}', [\App\Http\Controllers\RatingController::class, 'destroy'])->name('admin.opiniones.destroy');

    Route::get('/mensajes', function () {
        $messages = \App\Models\ContactMessage::orderByDesc('created_at')->get();
        return Inertia::render('Admin/Messages/Index', [
            'messages' => $messages,
        ]);
    })->name('admin.mensajes.index');

    Route::post('/descuento-general/aplicar', [\App\Http\Controllers\GeneralDiscountController::class, 'apply'])->name('admin.discount.apply');
    Route::post('/descuento-general/quitar', [\App\Http\Controllers\GeneralDiscountController::class, 'remove'])->name('admin.discount.remove');

    Route::get('/usuarios', [\App\Http\Controllers\UserController::class, 'index'])->name('admin.usuarios.index');
    Route::patch('/usuarios/{user}', [\App\Http\Controllers\UserController::class, 'update'])->name('admin.usuarios.update');
    Route::delete('/usuarios/{user}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('admin.usuarios.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [\App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success/{order}', [\App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::match(['patch', 'post'], '/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/api/pending-comments/skip/{order}', function (\App\Models\Order $order) {
        \App\Models\PendingComment::where('user_id', auth()->id())
            ->where('order_id', $order->id)
            ->where('has_commented', false)
            ->update(['has_commented' => true]);
        return response()->json(['success' => true]);
    })->name('api.pending-comments.skip');

    Route::get('/api/top-rated', function () {
        $top = Instrument::withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->where('disponible', true)
            ->where('stock', '>', 0)
            ->having('ratings_avg_rating', '>', 0)
            ->orderByDesc('ratings_avg_rating')
            ->take(10)
            ->get();
        return response()->json($top);
    })->name('api.top-rated');

    Route::get('/api/pending-comments', function () {
        if (auth()->user()->role === 'admin') {
            return response()->json(['count' => 0, 'items' => []]);
        }
        $pending = \App\Models\PendingComment::with('instrument')
            ->where('user_id', auth()->id())
            ->where('has_commented', false)
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'instrument_id' => $p->instrument_id,
                'instrument_name' => $p->instrument->marca . ' ' . $p->instrument->modelo,
                'order_id' => $p->order_id,
            ]);

        return response()->json([
            'count' => $pending->count(),
            'items' => $pending,
        ]);
    })->name('api.pending-comments');
});

require __DIR__.'/auth.php';
