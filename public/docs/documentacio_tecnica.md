# Documentació Tècnica - Tunely

**Projecte Transversal DAW2**  
**Tenda online d'instruments musicals**  
**Data:** Maig 2026

---

## Índex

1. [Arquitectura de l'aplicació](#1-arquitectura-de-laplicació)
2. [Estructura de fitxers](#2-estructura-de-fitxers)
3. [Base de dades](#3-base-de-dades)
4. [Autenticació i rols](#4-autenticació-i-rols)
5. [Catàleg de productes](#5-catàleg-de-productes)
6. [Procés de compra](#6-procés-de-compra)
7. [Sistema de valoracions](#7-sistema-de-valoracions)
8. [Gràfic Canvas](#8-gràfic-canvas)
9. [Tests](#9-tests)
10. [Manual d'instal·lació](#10-manual-dinstal·lació)

---

## 1. Arquitectura de l'aplicació

### Stack tecnològic

- **Backend:** Laravel 11 (PHP 8.2+)
- **Frontend:** Vue 3 + Inertia.js + Tailwind CSS
- **Base de dades:** SQLite (desenvolupament) / MySQL (producció)
- **Node.js:** 20.x per compilació d'assets Vite
- **Autenticació:** Laravel Breeze (Inertia + Vue)

### Patró arquitectònic

L'aplicació segueix el patró MVC de Laravel amb Inertia.js com a pont entre backend i frontend:

```
Petició HTTP → Ruta (web.php) → Middleware → Controller
  → Eloquent Model (DB) → Inertia::render() → Vue Page Component
  → Usuari interactua → useForm → POST/GET → Controller
```

El flux d'Inertia:

1. El Controller retorna `Inertia::render('Pagina', { props })`
2. Inertia envia JSON al frontend
3. Vue renderitza el component sense recarregar la pàgina
4. Formularis amb `useForm()` fan peticions Inertia (POST/PUT/DELETE)
5. El servidor retorna redirect amb flash messages

### Middleware destacat

- **HandleInertiaRequests:** Comparteix globalment `auth.user`, `flash.success`, `flash.error`, `cartCount`.
- **RoleMiddleware:** Redirigeix usuaris no admin a `/dashboard`.
- **CartMiddleware:** Calcula quants articles hi ha al carret via encadenament Inertia.

[CAPTURA: arquitectura_inertia.png]

---

## 2. Estructura de fitxers

```
Tunely/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/                  # Controladors Breeze
│   │   │   ├── Admin/
│   │   │   │   ├── CategoryController.php
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── InstrumentoController.php
│   │   │   │   ├── OrderController.php
│   │   │   │   ├── RatingController.php
│   │   │   │   └── SubcategoryController.php
│   │   │   ├── CheckoutController.php
│   │   │   ├── ContactController.php
│   │   │   ├── InstrumentoController.php   # Catàleg públic
│   │   │   ├── RatingController.php         # Valoracions usuari
│   │   │   └── ProfileController.php         # Perfil usuari
│   │   ├── Middleware/
│   │   │   └── RoleMiddleware.php
│   │   └── Requests/
│   │       ├── CheckoutRequest.php
│   │       └── ProfileUpdateRequest.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Instrumento.php
│   │   ├── Category.php
│   │   ├── Subcategory.php
│   │   ├── Order.php
│   │   ├── OrderItem.php
│   │   ├── Rating.php
│   │   ├── PendingComment.php
│   │   ├── ContactMessage.php
│   │   ├── Faq.php
│   │   └── ContactInfo.php
│   └── Providers/
│       └── AppServiceProvider.php
├── database/
│   └── migrations/
│       ├── 0001_01_01_000000_create_users_table.php
│       ├── 0001_01_01_000001_create_cache_table.php
│       ├── 0001_01_01_000002_create_jobs_table.php
│       ├── 2026_05_14_create_categories_table.php
│       ├── 2026_05_14_create_instrumentos_table.php
│       ├── 2026_05_14_add_role_to_users_table.php
│       ├── 2026_05_15_create_faqs_table.php
│       ├── 2026_05_15_create_contact_info_table.php
│       ├── 2026_05_17_create_orders_table.php
│       ├── 2026_05_17_create_order_items_table.php
│       ├── 2026_05_17_create_pending_comments_table.php
│       ├── 2026_05_17_create_ratings_table.php
│       ├── 2026_05_19_create_subcategories_table.php
│       ├── 2026_05_19_add_subcategory_id_to_instrumentos.php
│       ├── 2026_05_19_create_contact_messages_table.php
│       └── 2026_05_19_122325_add_shipping_fields_to_orders_table.php
├── resources/
│   ├── js/
│   │   ├── Components/          # Subcomponents Vue (Logo, CartDrawer, etc.)
│   │   ├── Layouts/             # AppLayout, AuthenticatedLayout, AdminLayout
│   │   └── Pages/               # Pàgines Inertia
│   │       ├── Admin/           # CRUD administració
│   │       └── *vue             # Pàgines públiques i usuari
│   └── views/
│       └── app.blade.php        # Template principal Inertia
├── public/
│   ├── img/                     # Logos i imatges
│   ├── video/                   # Vídeo presentació + subtítols
│   └── docs/                    # Documents de documentació
├── routes/
│   ├── web.php                  # Totes les rutes web
│   └── api.php                  # Ruta AJAX pending-comments
└── tests/
    └── Feature/
        ├── CheckoutTest.php
        ├── ContactTest.php
        ├── FaqTest.php
        ├── InstrumentoTest.php
        ├── OrderAdminTest.php
        ├── ProfileTest.php
        └── RatingTest.php
```

---

## 3. Base de dades

### Diagrama entitat-relació

[CAPTURA: diagrama_er.png]

### Taules principals

#### `users`

| Camp | Tipus | Descripció |
|------|-------|------------|
| id | integer PK | |
| name | string(255) | Nom complet |
| email | string(255) | Email únic |
| password | string(255) | Bcrypt |
| role | string(20) | 'admin' o 'client' |
| phone | string(50) | Telèfon |
| phone_country | string(10) | Prefix internacional |
| birth_date | date | Data de naixement |
| shipping_address | text | Adreça enviament |
| billing_address | text | Adreça facturació |
| preferred_instrument | string | Instrument preferit |
| experience_level | string | Principiant, Intermig, Avançat |
| newsletter | boolean | Subscripció |

#### `instrumentos`

| Camp | Tipus | Descripció |
|------|-------|------------|
| id | integer PK | |
| marca | string | Marca del fabricant |
| modelo | string | Model de l'instrument |
| tipo | string | Vent, corda, percussió |
| precio | decimal(10,2) | Preu unitari |
| stock | integer | Unitats disponibles |
| descripcion | text | Descripció detallada |
| imagen | string | Path de la imatge |
| category_id | integer FK | Categoria pare |
| subcategory_id | integer FK nullable | Subcategoria |
| disponible | boolean | Visible al catàleg |

#### `categories`

| Camp | Tipus |
|------|-------|
| id | integer PK |
| nombre | string(100) |
| slug | string(100) |

#### `subcategories`

| Camp | Tipus |
|------|-------|
| id | integer PK |
| nombre | string(100) |
| slug | string(100) |
| category_id | integer FK |

#### `orders`

| Camp | Tipus | Descripció |
|------|-------|------------|
| id | integer PK | Número de comanda |
| user_id | integer FK | Client |
| total | decimal(10,2) | Total |
| status | string | Pendiente/Pagado/Enviado/Entregado/Cancelado |
| shipping_name | string | Nom destinatari |
| shipping_address | text | Carrer |
| shipping_city | string | Ciutat |
| shipping_province | string | Província |
| shipping_postal_code | string | CP |
| shipping_phone | string | Telèfon |
| billing_name | string | Nom facturació |
| billing_address | text | Carrer facturació |
| billing_city | string | Ciutat facturació |
| billing_province | string | Província facturació |
| billing_postal_code | string | CP facturació |

#### `order_items`

| Camp | Tipus |
|------|-------|
| id | integer PK |
| order_id | integer FK |
| instrumento_id | integer FK |
| quantity | integer |
| price | decimal(10,2) |

#### `ratings`

| Camp | Tipus |
|------|-------|
| id | integer PK |
| user_id | integer FK |
| instrumento_id | integer FK |
| rating | integer (1-5) |
| comentario | text |
| *unique* | (user_id, instrumento_id) |

#### `pending_comments`

| Camp | Tipus |
|------|-------|
| id | integer PK |
| user_id | integer FK |
| instrumento_id | integer FK |
| created_at | timestamp |

#### `faqs`, `contact_info`, `contact_messages`

Taules auxiliars.

### SQLite vs MySQL

En desenvolupament s'usa SQLite per simplicitat. Migrar a MySQL implica canviar `DB_CONNECTION=mysql` al `.env` i ajustar alguns tipus (p. ex. `text` per adreces).

[CAPTURA: estructura_bd.png]

---

## 4. Autenticació i rols

### Registre amb camps extra

El `RegisteredUserController` s'ha modificat per incloure tots els camps definits a la migració `users`:

```php
// app/Http/Controllers/Auth/RegisteredUserController.php
$request->validate([
    'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-ZÀ-ÿ\s]+$/'],
    'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
    'password' => ['required', 'confirmed', Rules\Password::defaults()],
    'birth_date' => ['required', 'date', 'before:100 years ago', 'after:18 years ago'],
    'phone' => ['required', 'string', 'max:20'],
    'shipping_address' => ['required', 'string', 'max:500'],
    'preferred_instrument' => ['nullable', 'string', 'max:100'],
    'experience_level' => ['nullable', 'string', 'in:Principiante,Intermedio,Avanzado'],
]);
```

### RoleMiddleware

```php
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! $request->user() || $request->user()->role !== $role) {
            return redirect('/dashboard');
        }
        return $next($request);
    }
}
```

### Rutes admin

```php
// routes/web.php
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/productos', Admin\InstrumentoController::class);
    Route::resource('/categorias', Admin\CategoryController::class);
    Route::resource('/subcategorias', Admin\SubcategoryController::class);
    Route::get('/pedidos', [Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/pedidos/{order}', [Admin\OrderController::class, 'show'])->name('orders.show');
    Route::patch('/pedidos/{order}/status', [Admin\OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('/bulk-discount', [Admin\InstrumentoController::class, 'bulkDiscount'])->name('bulk-discount');
    Route::get('/opiniones', [Admin\RatingController::class, 'index'])->name('ratings.index');
    Route::delete('/opiniones/{rating}', [Admin\RatingController::class, 'destroy'])->name('ratings.destroy');
    Route::get('/mensajes', [Admin\ContactMessageController::class, 'index'])->name('messages.index');
});
```

[CAPTURA: rutes_admin.png]

---

## 5. Catàleg de productes

### InstrumentoController (públic)

```php
class InstrumentoController extends Controller
{
    public function index(Request $request)
    {
        $query = Instrumento::with('category', 'subcategory')
            ->where('disponible', true)->where('stock', '>', 0);

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        $instrumentos = $query->paginate(12);
        $categorias = Category::all();

        return Inertia::render('Instrumentos/Index', [
            'instrumentos' => $instrumentos,
            'categorias' => $categorias,
            'filters' => $request->only('category'),
        ]);
    }

    public function show(Instrumento $instrumento)
    {
        $instrumento->load('category', 'subcategory', 'ratings.user');
        $userRating = auth()->check()
            ? $instrumento->ratings->firstWhere('user_id', auth()->id())
            : null;
        $canRate = auth()->check() && PendingComment::where('user_id', auth()->id())
            ->where('instrumento_id', $instrumento->id)->exists();

        return Inertia::render('Instrumentos/Detalle', [
            'instrumento' => $instrumento,
            'userRating' => $userRating,
            'canRate' => $canRate,
        ]);
    }
}
```

### Filtrat per categoria

El filtrat es fa passant `?category=slug` a la URL. Inertia manté l'estat del filtre.

[CAPTURA: catalogo_categoria.png]

### Descompte global

```php
public function bulkDiscount(Request $request)
{
    $request->validate(['percent' => 'required|numeric|min:1|max:99']);
    $count = Instrumento::where('disponible', true)->where('stock', '>', 0)->count();
    Instrumento::where('disponible', true)->where('stock', '>', 0)
        ->update(['precio' => DB::raw("precio * (1 - {$request->percent} / 100)")]);
    return redirect()->back()->with('success', "Descuento del {$request->percent}% aplicado a {$count} productos.");
}
```

---

## 6. Procés de compra

### CheckoutController

```php
class CheckoutController extends Controller
{
    public function index()
    {
        return Inertia::render('Checkout', [
            'cartItems' => session('cart', []),
        ]);
    }

    public function store(CheckoutRequest $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) return redirect()->back()->with('error', 'El carrito está vacío.');

        DB::transaction(function () use ($request, $cart, &$order) {
            $total = 0;
            foreach ($cart as $item) {
                $producto = Instrumento::findOrFail($item['id']);
                if ($producto->stock < $item['quantity']) throw new \Exception("Stock insuficiente");
                $total += $producto->precio * $item['quantity'];
            }

            $data = $request->validated();
            $order = Order::create([...$data, 'user_id' => auth()->id(), 'total' => $total, 'status' => 'Pendiente']);

            foreach ($cart as $item) {
                $producto = Instrumento::findOrFail($item['id']);
                OrderItem::create(['order_id' => $order->id, 'instrumento_id' => $producto->id, 'quantity' => $item['quantity'], 'price' => $producto->precio]);
                PendingComment::create(['user_id' => auth()->id(), 'instrumento_id' => $producto->id]);
                $producto->decrement('stock', $item['quantity']);
            }
        });

        session()->forget('cart');
        return redirect()->route('orders.success', $order);
    }
}
```

### Màquina d'estats

```php
// Admin/OrderController.php
private const VALID_TRANSITIONS = [
    'Pendiente' => ['Pagado', 'Cancelado'],
    'Pagado'    => ['Enviado', 'Cancelado'],
    'Enviado'   => ['Entregado', 'Cancelado'],
    'Entregado' => [],
    'Cancelado' => [],
];

public function updateStatus(Request $request, Order $order)
{
    $request->validate(['status' => 'required|string|in:Pagado,Enviado,Entregado,Cancelado']);
    if (!in_array($request->status, self::VALID_TRANSITIONS[$order->status] ?? [])) {
        return redirect()->back()->with('error', 'Transición de estado no válida.');
    }
    $order->update(['status' => $request->status]);
    return redirect()->back()->with('success', 'Estado actualizado correctamente.');
}
```

### CheckoutRequest (validació)

```php
class CheckoutRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'shipping_name' => 'required|string|max:255',
            'shipping_address' => 'required|string|max:500',
            'shipping_city' => 'required|string|max:100',
            'shipping_province' => 'required|string|max:100',
            'shipping_postal_code' => 'required|string|size:5',
            'shipping_phone' => 'required|string|max:20',
            'same_as_shipping' => 'boolean',
            'billing_name' => 'required_if:same_as_shipping,false|string|max:255',
            'billing_address' => 'required_if:same_as_shipping,false|string|max:500',
            'billing_city' => 'required_if:same_as_shipping,false|string|max:100',
            'billing_province' => 'required_if:same_as_shipping,false|string|max:100',
            'billing_postal_code' => 'required_if:same_as_shipping,false|string|size:5',
            'card_number' => 'required|string|size:16',
            'card_expiry' => 'required|string|size:5',
            'card_cvv' => 'required|string|size:3',
        ];
    }
}
```

[CAPTURA: checkout_validacio.png]

### Carret (LocalStorage)

El carret no requereix sessió al servidor. S'emmagatzema a `localStorage` del navegador i es passa via Inertia share:

```js
// AppLayout.vue
const cart = ref(JSON.parse(localStorage.getItem('cart') || '[]'));
const cartCount = computed(() => cart.value.reduce((sum, item) => sum + item.quantity, 0));
```

[CAPTURA: carret_desplegable.png]

---

## 7. Sistema de valoracions

### Creació de valoració

```php
// RatingController.php (usuari)
public function store(Request $request)
{
    $request->validate([
        'instrumento_id' => 'required|exists:instrumentos,id',
        'rating' => 'required|integer|min:1|max:5',
        'comentario' => 'nullable|string|max:1000',
    ]);

    $existing = PendingComment::where('user_id', auth()->id())
        ->where('instrumento_id', $request->instrumento_id)->first();
    if (!$existing) return redirect()->back()->with('error', 'No puedes valorar este producto.');

    Rating::updateOrCreate(
        ['user_id' => auth()->id(), 'instrumento_id' => $request->instrumento_id],
        ['rating' => $request->rating, 'comentario' => $request->comentario]
    );

    $existing->delete();
    return redirect()->back()->with('success', 'Valoración guardada.');
}
```

### Recordatori AJAX

```js
// AppLayout.vue - al mount()
fetch('/api/pending-comments')
  .then(res => res.json())
  .then(data => {
    if (data.length > 0) {
      pendingCount.value = data.length;
      showBanner.value = true;
    }
  });
```

[CAPTURA: banner_valoracions_pendents.png]

### Admin gestió valoracions

L'admin pot llistar i eliminar valoracions indegudes.

[CAPTURA: admin_opinions.png]

### Unique constraint

`$table->unique(['user_id', 'instrumento_id'])` — un usuari només pot valorar un cop cada producte.

---

## 8. Gràfic Canvas

### Backend (DashboardController)

```php
public function index()
{
    $topProducts = OrderItem::select('instrumento_id',
        DB::raw('SUM(quantity) as total_vendido'),
        DB::raw('SUM(order_items.price * quantity) as total_ingresos'))
        ->groupBy('instrumento_id')
        ->orderByDesc('total_vendido')
        ->take(10)
        ->with('instrumento')
        ->get();

    return Inertia::render('Admin/Dashboard', [
        'stats' => [...],
        'topProducts' => $topProducts,
    ]);
}
```

### Frontend (Canvas JS pur)

```vue
<canvas ref="chartCanvas" width="600" height="300"></canvas>

<script setup>
import { ref, onMounted } from 'vue';
const chartCanvas = ref(null);

onMounted(() => {
  const canvas = chartCanvas.value;
  const ctx = canvas.getContext('2d');
  const products = props.topProducts;
  const max = Math.max(...products.map(p => p.total_vendido));

  products.forEach((p, i) => {
    const barHeight = (p.total_vendido / max) * 250;
    const x = 40 + i * 55;
    const y = 270 - barHeight;

    const gradient = ctx.createLinearGradient(x, y, x, 270);
    gradient.addColorStop(0, '#E87F24');
    gradient.addColorStop(1, '#73A5CA');
    ctx.fillStyle = gradient;
    ctx.fillRect(x, y, 40, barHeight);
  });
});
</script>
```

[CAPTURA: grafic_canvas.png]

---

## 9. Tests

### Suite de tests (43 tests, 162 assertions)

Tots els tests usen `RefreshDatabase` i `assertInertia` per verificar respostes Inertia.

| Test Fitxer | Assertions | Descripció |
|-------------|-----------|------------|
| CheckoutTest | | Procés de compra complet, validació, stock |
| ContactTest | | Enviament i recepció de missatges |
| FaqTest | | FAQ carreguen des de DB |
| InstrumentoTest | | Catàleg, filtrat, detall |
| OrderAdminTest | | CRUD comandes, màquina d'estats |
| ProfileTest | | Actualització perfil |
| RatingTest | | Valoracions, unique, admin |
| **Total** | **162** | |

### Exemple de test

```php
class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_complete_checkout()
    {
        $user = User::factory()->create(['role' => 'client']);
        $product = Instrumento::factory()->create(['stock' => 5, 'precio' => 100]);

        session(['cart' => [['id' => $product->id, 'quantity' => 2]]]);

        $response = $this->actingAs($user)->post(route('checkout.store'), [
            'shipping_name' => 'Test User',
            'shipping_address' => 'Carrer Test 123',
            'shipping_city' => 'Barcelona',
            'shipping_province' => 'Barcelona',
            'shipping_postal_code' => '08001',
            'shipping_phone' => '+34 600 000 000',
            'same_as_shipping' => true,
            'card_number' => '1234567890123456',
            'card_expiry' => '12/28',
            'card_cvv' => '123',
        ]);

        $response->assertRedirect(route('orders.success', Order::first()));
        $this->assertEquals(3, $product->fresh()->stock);
        $this->assertDatabaseHas('orders', ['user_id' => $user->id, 'total' => 200]);
    }
}
```

[CAPTURA: tests_passing.png]

### Execució

```bash
# Executar tots els tests
php artisan test

# Executar un fitxer concret
php artisan test tests/Feature/CheckoutTest.php
```

---

## 10. Manual d'instal·lació

### Requisits

- PHP 8.2+
- Composer 2.x
- Node.js 20.x + npm
- SQLite (inclòs a PHP)

### Passos

```bash
# 1. Clonar repositori
git clone <url-repositori>
cd Tunely

# 2. Instal·lar dependències PHP
composer install

# 3. Instal·lar dependències Node.js
npm install

# 4. Copiar .env i generar key
copy .env.example .env
php artisan key:generate

# 5. Configurar .env (per defecte SQLite funciona sense canvis)
# DB_CONNECTION=sqlite

# 6. Crear base de dades SQLite
# (tocar database/database.sqlite o deixar que la migració el cree)

# 7. Migrar i poblar DB
php artisan migrate --seed

# 8. Compilar assets
npm run build

# 9. Enllaçar storage
php artisan storage:link

# 10. Servidor de desenvolupament
php artisan serve
# http://localhost:8000
```

### Usuaris per defecte (seeder)

| Rol | Email | Contrasenya |
|-----|-------|-------------|
| Admin | `admin@tunely.es` | `password` |
| Client | `client@tunely.es` | `password` |

[CAPTURA: instalacio_completada.png]

---

**Fi de la documentació tècnica**
