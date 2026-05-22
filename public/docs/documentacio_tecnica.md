# Documentación Técnica - Tunely

**Proyecto Transversal DAW2**
**Tienda online de instrumentos musicales**
**Stack:** Laravel 12 + Inertia + Vue 3 + Tailwind CSS 4
**Fecha:** Mayo 2026

---

## Índice

1. [Arquitectura de la aplicación](#1-arquitectura-de-la-aplicación)
2. [Estructura de archivos](#2-estructura-de-archivos)
3. [Base de datos](#3-base-de-datos)
4. [Autenticación y Usuarios](#4-autenticación-y-usuarios)
5. [Catálogo de Instrumentos](#5-catálogo-de-instrumentos)
6. [Proceso de compra (Carrito + Checkout)](#6-proceso-de-compra-carrito--checkout)
7. [Sistema de Valoraciones](#7-sistema-de-valoraciones)
8. [Sistema de Descuentos Generales](#8-sistema-de-descuentos-generales)
9. [Dashboard de Administrador](#9-dashboard-de-administrador)
10. [CRUD de Instrumentos](#10-crud-de-instrumentos)
11. [Gráfico Canvas](#11-gráfico-canvas)
12. [Seeders](#12-seeders)
13. [Manual de instalación](#13-manual-de-instalación)
14. [API REST (Pràctica 3)](#14-api-rest-pràctica-3)
15. [App Ionic (Pràctica 3)](#15-app-ionic-pràctica-3)
16. [Tests d'errors (Pràctica 3)](#16-tests-derrors-pràctica-3)

---

## 1. Arquitectura de la aplicación

### Stack tecnológico

| Capa | Tecnología | Versión |
|------|-----------|---------|
| Backend | Laravel | ^12.0 |
| Frontend | Vue 3 + Inertia | ^3.5 / ^2.0 |
| CSS | Tailwind CSS | ^4.0 |
| Base de datos | SQLite (dev) / MySQL (prod) | - |
| Autenticación | Laravel Breeze (Inertia + Vue) | ^2.x |
| Build | Vite | ^7.3 |
| Rutas JS | Ziggy | ^2.0 |
| API REST | Laravel (routes/api.php) | Pràctica 3 DWES |
| App mòbil | Ionic + Vue 3 (Tabs) | Pràctica 3 DWES |

### Flujo de datos Inertia

```
Petición HTTP → Route (web.php) → Middleware → Controller/Closure
  → Eloquent Model (BD) → Inertia::render('Componente', { props })
  → Inertia devuelve JSON con nombre del componente + props
  → Vue renderiza el componente SIN recargar la página
  → Usuario interactúa → useForm → POST/PUT/DELETE → Controller
  → Controller valida + procesa → redirect con flash messages
  → Inertia re-renderiza la nueva página
```

**Justificación técnica:** Inertia permite construir una SPA sin necesidad de API REST ni Vue Router. Cada ruta Laravel se asigna a un componente Vue. El servidor renderiza la página completa (SSR-ready) y las navegaciones posteriores son AJAX. Esto simplifica la autenticación (cookies/session, no JWT) y elimina la duplicación de lógica de rutas.

### Middleware destacado

| Middleware | Propósito | Registro en |
|-----------|-----------|-------------|
| `HandleInertiaRequests` | Comparte datos globales con Inertia (user, flash messages) | `bootstrap/app.php` |
| `CheckRole` | Verifica si el usuario tiene el rol requerido | `bootstrap/app.php` (alias `role`) |
| `auth` | Verifica sesión activa | Laravel Breeze |
| `verified` | Verifica email confirmado | Laravel Breeze |

### Roles de usuario

| Rol | Acceso | Asignación |
|-----|--------|------------|
| `admin` | Panel de administración completo | Manual (seeder o edición desde admin) |
| `client` | Compra, perfil, historial, valoraciones | Automático (registro) |

---

## 2. Estructura de archivos

```
Tunely/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/                       # Controladores Breeze
│   │   │   ├── CheckoutController.php      # Procesa pedidos
│   │   │   ├── ContactController.php       # Formulario contacto
│   │   │   ├── GeneralDiscountController.php # Descuentos generales
│   │   │   ├── OrderController.php         # Gestión pedidos admin
│   │   │   ├── ProfileController.php       # Perfil usuario
│   │   │   ├── RatingController.php         # Valoraciones
│   │   │   ├── UserController.php           # Gestión usuarios admin
│   │   │   ├── CategoryController.php       # CRUD categorías
│   │   │   ├── SubcategoryController.php    # CRUD subcategorías
│   │   │   └── InstrumentController.php     # CRUD instrumentos
│   │   └── Middleware/
│   │       └── CheckRole.php               # Middleware de roles
│   ├── Models/
│   │   ├── User.php
│   │   ├── Instrument.php
│   │   ├── Category.php
│   │   ├── Subcategory.php
│   │   ├── Order.php
│   │   ├── OrderItem.php
│   │   ├── Rating.php
│   │   ├── PendingComment.php
│   │   ├── ContactMessage.php
│   │   ├── ContactInfo.php
│   │   ├── Faq.php
│   │   └── GeneralDiscount.php
│   └── Providers/
│       └── AppServiceProvider.php
├── database/
│   ├── migrations/                         # 16 migraciones
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── AdminUserSeeder.php
│       ├── CategorySeeder.php
│       ├── SubcategorySeeder.php
│       └── InstrumentSeeder.php
├── resources/
│   ├── js/
│   │   ├── Components/                     # Componentes reutilizables
│   │   │   ├── CartDrawer.vue              # Drawer carrito
│   │   │   ├── ProductCarousel.vue         # Carrusel portada
│   │   │   ├── TextInput.vue               # Input estilizado
│   │   │   └── InputError.vue              # Mensaje error
│   │   ├── Layouts/
│   │   │   ├── AppLayout.vue               # Layout público
│   │   │   ├── AdminLayout.vue             # Layout admin
│   │   │   ├── GuestLayout.vue             # Layout auth
│   │   │   └── AuthenticatedLayout.vue     # Layout usuario
│   │   └── Pages/
│   │       ├── Auth/                       # Login, Register
│   │       ├── Profile/                    # Editar perfil
│   │       ├── Admin/                      # Panel admin
│   │       │   ├── Dashboard.vue           # Estadísticas
│   │       │   ├── Instruments/            # CRUD productos
│   │       │   ├── Categories/             # CRUD categorías
│   │       │   ├── Subcategories/          # CRUD subcategorías
│   │       │   └── ...                     # Orders, Ratings, etc.
│   │       ├── Welcome.vue                 # Portada
│   │       ├── Catalogo.vue                # Catálogo público
│   │       ├── InstrumentoDetalle.vue      # Detalle producto
│   │       ├── Checkout.vue                # Finalizar compra
│   │       ├── OrderSuccess.vue            # Confirmación pedido
│   │       ├── Dashboard.vue               # Mis pedidos
│   │       ├── MisValoraciones.vue         # Mis valoraciones
│   │       ├── QuienSomos.vue              # Página presentación
│   │       ├── Contacto.vue                # Formulario contacto
│   │       ├── FAQ.vue                     # Preguntas frecuentes
│   │       ├── AvisoLegal.vue              # Aviso legal
│   │       ├── Privacidad.vue              # Política privacidad
│   │       └── Condiciones.vue             # Condiciones envío
│   └── views/
│       └── app.blade.php                   # Layout raíz Inertia
├── public/
│   ├── img/
│   │   ├── carrusel/                       # 5 imágenes .webp
│   │   ├── profiles/                       # Fotos de perfil
│   │   ├── productos/                      # Imágenes de productos
│   │   └── tunely_logo.png                # Logotipo
│   ├── video/
│   │   ├── videoMarketing.mp4             # Video presentación
│   │   └── presentacion.vtt               # Subtítulos
│   └── docs/                              # Documentación
├── routes/
│   ├── web.php                            # Todas las rutas web
│   └── auth.php                           # Rutas de autenticación Breeze
└── tests/
    └── Feature/                           # Tests (si existen)
```

---

## 3. Base de datos

### Diagrama entidad-relación (tablas)

```
users ──1:N──> orders ──1:N──> order_items ──N:1──> instruments
  │                 │
  │                 └──> pending_comments
  │                       │
  └──> ratings ────N:1──┘
  │
  └──> contact_messages

categories ──1:N──> subcategories ──1:N──> instruments
```

### Tabla `users`

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | integer PK | |
| name | string(255) | Nombre |
| surname | string(255) | Primer apellido |
| second_surname | string(255) nullable | Segundo apellido |
| email | string(255) unique | Email |
| password | string(255) | Bcrypt hashed |
| role | string(20) default 'client' | admin / client |
| fecha_nacimiento | date | Fecha nacimiento |
| telefono | string(50) | Teléfono |
| direccion | text | Dirección envío |
| ciudad | string(100) | |
| provincia | string(100) | |
| codigo_postal | string(10) | |
| mismo_direccion_facturacion | boolean | |
| direccion_facturacion | text nullable | |
| ciudad_facturacion | string(100) nullable | |
| provincia_facturacion | string(100) nullable | |
| codigo_postal_facturacion | string(10) nullable | |
| instrumento_preferido | string(100) nullable | |
| nivel_experiencia | string(50) nullable | |
| foto | string(255) nullable | Ruta foto perfil |
| email_verified_at | timestamp nullable | |
| remember_token | string(100) nullable | |

### Tabla `instruments`

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | integer PK | |
| marca | string(255) | Marca del fabricante |
| modelo | string(255) | Modelo |
| tipo | string(50) | 'nuevo' o 'usado' |
| precio | decimal(10,2) | Precio base (sin IVA) |
| precio_original | decimal(10,2) nullable | Precio antes de descuento |
| stock | integer | Unidades disponibles |
| descripcion | text nullable | Descripción detallada |
| imagen | string(255) nullable | Ruta de la imagen |
| disponibilidad | string(50) default 'disponible' | Estado |
| category_id | integer FK | Categoría |
| subcategory_id | integer FK nullable | Subcategoría |
| disponible | boolean default true | Visible en catálogo |
| descuento_general_applied | boolean default false | Afectado por descuento general |
| created_at | timestamp | |
| updated_at | timestamp | |

**Accessors:**
```php
public function getPrecioConIvaAttribute()
{
    return round($this->precio * 1.21, 2);
}
```
El atributo `precio_con_iva` se incluye automáticamente en las respuestas gracias a `$appends = ['precio_con_iva']`.

### Tabla `categories`

| Campo | Tipo |
|-------|------|
| id | integer PK |
| nombre | string(100) |
| created_at | timestamp |
| updated_at | timestamp |

### Tabla `subcategories`

| Campo | Tipo |
|-------|------|
| id | integer PK |
| nombre | string(100) |
| category_id | integer FK |
| created_at | timestamp |
| updated_at | timestamp |

### Tabla `orders`

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | integer PK | Número de pedido |
| user_id | integer FK | Cliente |
| total | decimal(10,2) | Total del pedido |
| estado | string(50) | Pendiente/Pagado/Enviado/Entregado/Cancelado |
| shipping_name | string(255) | Nombre destinatario |
| shipping_address | text | Dirección envío |
| shipping_city | string(100) | |
| shipping_province | string(100) | |
| shipping_postal_code | string(10) | |
| shipping_phone | string(50) | |
| billing_same_as_shipping | boolean | |
| billing_name | string(255) nullable | |
| billing_address | text nullable | |
| billing_city | string(100) nullable | |
| billing_province | string(100) nullable | |
| billing_postal_code | string(10) nullable | |
| created_at | timestamp | |
| updated_at | timestamp | |

### Tabla `order_items`

| Campo | Tipo |
|-------|------|
| id | integer PK |
| order_id | integer FK |
| instrument_id | integer FK |
| cantidad | integer |
| precio_unitario | decimal(10,2) |

### Tabla `ratings`

| Campo | Tipo |
|-------|------|
| id | integer PK |
| user_id | integer FK |
| instrument_id | integer FK |
| rating | integer (1-5) |
| comment | text nullable |
| created_at | timestamp |
| *unique* | (user_id, instrument_id) |

### Tabla `pending_comments`

| Campo | Tipo |
|-------|------|
| id | integer PK |
| user_id | integer FK |
| instrument_id | integer FK |
| order_id | integer FK nullable |
| has_commented | boolean default false |
| created_at | timestamp |

### SQLite vs MySQL

En desarrollo se usa SQLite por simplicidad (archivo `database/database.sqlite`). Migrar a MySQL implica cambiar `DB_CONNECTION=mysql` en `.env` y crear la base de datos. Las migraciones son compatibles con ambos motores.

---

## 4. Autenticación y Usuarios

### 4.1 Registro

**Archivos:**
- `app/Http/Controllers/Auth/RegisteredUserController.php`
- `resources/js/Pages/Auth/Register.vue`
- `routes/auth.php`

**Validación backend:**

```php
$request->validate([
    'nombre' => 'required|string|max:255|regex:/^[\pL\s]+$/u',
    'apellido1' => 'required|string|max:255|regex:/^[\pL\s]+$/u',
    'fecha_nacimiento' => 'required|date|before:' . now()->subYears(18)->format('Y-m-d') . '|after:' . now()->subYears(100)->format('Y-m-d'),
    'telefono' => 'required|string|max:20',
    'email' => 'required|string|email|max:255|unique:' . User::class,
    'password' => 'required|string|min:8|confirmed',
]);
```

**Máscara de fecha DD/MM/AAAA (frontend):**

```javascript
const onFechaInput = (e) => {
    const raw = e.target.value;
    const digits = raw.replace(/\D/g, '').slice(0, 8);
    let formatted = '';
    if (digits.length > 0) formatted = digits.slice(0, 2);
    if (digits.length > 2) formatted += '/' + digits.slice(2, 4);
    if (digits.length > 4) formatted += '/' + digits.slice(4, 8);
    fechaDisplay.value = formatted;
    if (digits.length === 8) {
        form.fecha_nacimiento = `${digits.slice(4,8)}-${digits.slice(2,4)}-${digits.slice(0,2)}`;
    }
};
```

Se usa un `<input>` nativo (no `TextInput`) porque el componente `TextInput` usa `defineModel` que interferiría con el control manual del valor. El input muestra DD/MM/AAAA pero internamente `form.fecha_nacimiento` se guarda como YYYY-MM-DD (ISO 8601) para compatibilidad con la base de datos.

**Indicador de fortaleza de contraseña:**

```javascript
const passwordStrength = computed(() => {
    const p = form.password;
    if (!p) return 0;
    let score = 0;
    if (p.length >= 8) score++;
    if (p.length >= 12) score++;
    if (/[a-z]/.test(p) && /[A-Z]/.test(p)) score++;
    if (/\d/.test(p)) score++;
    if (/[^a-zA-Z0-9]/.test(p)) score++;
    return score;
});
```

Se muestra con `<meter :value="passwordStrength" min="0" max="5">` y etiqueta de texto (Débil/Media/Fuerte).

**Indicadores visuales foco/blur/válido:**

```javascript
const fieldClass = (field, hasValue) => {
    const classes = [];
    if (focusedField.value === field) classes.push('ring-2 ring-[#E87F24]');
    if (form.errors[field]) classes.push('border-red-500 ring-1 ring-red-500');
    else if (touchedFields.value[field] && hasValue) classes.push('border-green-500');
    return classes.join(' ');
};
```

**Justificación:** Tres estados visuales diferenciados: foco (anillo naranja), error (borde rojo), válido (borde verde). El verde solo se aplica tras perder el foco (`touchedFields`), evitando marcar campos no tocados.

### 4.2 Roles y Middleware

**Middleware `CheckRole`:**

```php
class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! $request->user() || $request->user()->role !== $role) {
            abort(403);
        }
        return $next($request);
    }
}
```

**Registro en `bootstrap/app.php`:**

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\CheckRole::class,
    ]);
})
```

**Uso en rutas:**

```php
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->group(function () {
    // ...
});
```

**Justificación:** Preferimos middleware a policies porque cada usuario tiene exactamente un rol. El middleware intercepta antes del controlador, lo que es más eficiente que verificar permisos en cada método.

### 4.3 Edición de perfil y foto

```php
public function update(Request $request)
{
    $user = $request->user();
    if ($request->hasFile('foto')) {
        // Eliminar foto anterior
        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
        }
        $path = $request->file('foto')->store('profiles', 'public');
        $user->foto = $path;
    }
    $user->save();
}
```

Las imágenes se almacenan en `storage/app/public/profiles/` y se sirven desde `/storage/profiles/` mediante el enlace simbólico de Laravel.

---

## 5. Catálogo de Instrumentos

### 5.1 Listado público

**Archivo:** `routes/web.php` (closure en ruta `/catalogo`)

```php
Route::get('/catalogo', function () {
    $instruments = Instrument::with('category')
        ->where('disponible', true)
        ->where('stock', '>', 0)
        ->get();
    $categories = Category::all();
    return Inertia::render('Catalogo', [
        'instruments' => $instruments,
        'categories' => $categories,
    ]);
})->name('catalogo');
```

**Filtro por categoría (frontend):**

```javascript
const selectedCategory = ref(null);
const filteredInstruments = computed(() => {
    if (!selectedCategory.value) return instruments;
    return instruments.filter(i => i.category_id === selectedCategory.value);
});
```

**Justificación:** El filtro es local (client-side) porque el catálogo tiene solo ~31 productos. Para catálogos grandes se haría server-side con query params y paginación.

### 5.2 Detalle de producto

**Ruta:**

```php
Route::get('/catalogo/{id}', function ($id) {
    $instrument = Instrument::with('category', 'ratings.user')->findOrFail($id);
    // ...
    return Inertia::render('InstrumentoDetalle', [
        'instrument' => $instrument,
        'userRating' => $userRating,
        'canRate' => $canRate,
    ]);
})->name('instrumento.detalle');
```

### 5.3 Formulario de consulta de producto

**Archivo:** `resources/js/Pages/InstrumentoDetalle.vue`

El formulario de consulta se adapta según el estado de autenticación:

```vue
<div v-if="!isLoggedIn">
    <TextInput v-model="queryForm.name" placeholder="Nombre *" />
    <TextInput v-model="queryForm.email" placeholder="Email *" />
</div>
<textarea v-model="queryForm.message" maxlength="150"></textarea>
<button v-if="queryValid && !form.processing" @click="submitQuery">Enviar consulta</button>
```

El botón de envío solo aparece cuando `queryValid` es true (nombre, email y mensaje completos). Cuando el usuario está logueado, `queryForm.name` y `queryForm.email` se rellenan automáticamente desde `$page.props.auth.user`.

---

## 6. Proceso de compra (Carrito + Checkout)

### 6.1 Carrito con localStorage

**Archivo:** `resources/js/Layouts/AppLayout.vue`

```javascript
const STORAGE_KEY = 'tunely_cart';

const loadCart = () => {
    const stored = localStorage.getItem(STORAGE_KEY);
    if (stored) cart.value = JSON.parse(stored);
};

const addToCart = (product) => {
    const existing = cart.value.find(item => item.id === product.id);
    if (existing) {
        existing.quantity += 1;
    } else {
        cart.value.push({ id, marca, modelo, precio, imagen, quantity: 1 });
    }
    saveCart();
};
```

**Evento personalizado:** Se usa `window.dispatchEvent(new CustomEvent('add-to-cart', { detail: instrument }))` para que cualquier componente pueda añadir al carrito, incluso si está anidado profundamente. `AppLayout` escucha el evento y procesa la adición.

### 6.2 Checkout

**Archivos:**
- `app/Http/Controllers/CheckoutController.php`
- `resources/js/Pages/Checkout.vue`

**Controlador:**

```php
public function store(Request $request)
{
    $cart = json_decode($request->cart, true);
    if (empty($cart)) return back()->with('error', 'El carrito está vacío');

    DB::transaction(function () use ($cart, &$order) {
        $total = 0;
        $items = [];
        foreach ($cart as $item) {
            $product = Instrument::findOrFail($item['id']);
            $total += $product->precio * $item['quantity'];
            $items[] = new OrderItem([
                'instrument_id' => $product->id,
                'cantidad' => $item['quantity'],
                'precio_unitario' => $product->precio,
            ]);
            $product->decrement('stock', $item['quantity']);
        }
        $order = Order::create([...]);
        $order->items()->saveMany($items);
        // Crear PendingComment para cada producto
    });
    // ...
}
```

**Justificación:** El stock se decrementa al crear el pedido, no al añadir al carrito. Esto es intencional: el carrito es local al navegador y no está sincronizado con el servidor. Si dos usuarios tienen el mismo producto en su carrito, el stock se descuenta cuando el primero finaliza la compra.

### 6.3 Página de confirmación (OrderSuccess)

**Archivo:** `resources/js/Pages/OrderSuccess.vue`

Muestra el resumen del pedido y las opciones de valoración post-compra:

```vue
<div v-if="!ratingDismissed && !isAdmin">
    <Link v-for="item in order.items" :href="'/catalogo/' + item.instrument_id">
        Valorar {{ item.instrument.marca }} {{ item.instrument.modelo }}
    </Link>
    <button @click="skipRating">No valorar</button>
    <button @click="dismissRating">Ahora no</button>
</div>
```

- **Valorar:** Enlaza al detalle del producto para dejar valoración.
- **No valorar:** Llama a `POST /api/pending-comments/skip/{order}` que marca todos los `PendingComment` del pedido como `has_commented = true`.
- **Ahora no:** Solo oculta el banner localmente (no afecta a BD).

Al cargar la página, se ejecuta `localStorage.removeItem('tunely_cart')` para limpiar el carrito.

---

## 7. Sistema de Valoraciones

### 7.1 Creación de valoración

**Archivo:** `app/Http/Controllers/RatingController.php`

```php
public function store(Request $request, Instrument $instrument): RedirectResponse
{
    $validated = $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:1000',
    ]);

    $existing = Rating::where('user_id', auth()->id())
        ->where('instrument_id', $instrument->id)
        ->first();

    if ($existing) {
        return back()->withErrors(['rating' => 'Ya has valorado este instrumento']);
    }

    $rating = Rating::create([
        'user_id' => auth()->id(),
        'instrument_id' => $instrument->id,
        'rating' => $validated['rating'],
        'comment' => $validated['comment'] ?? null,
    ]);

    // Marcar pending_comment como completado
    PendingComment::where('user_id', auth()->id())
        ->where('instrument_id', $instrument->id)
        ->where('has_commented', false)
        ->update(['has_commented' => true]);

    return back()->with('success', 'Valoración enviada correctamente');
}
```

**Unique constraint:** La migración `ratings` tiene `$table->unique(['user_id', 'instrument_id'])` para que un usuario solo pueda valorar un producto una vez. El controlador también verifica antes de crear.

### 7.2 Recordatorio AJAX

**Archivo:** `resources/js/Layouts/AppLayout.vue` (onMounted)

```javascript
fetch(route('api.pending-comments'))
    .then(r => r.json())
    .then(data => {
        pendingCount.value = data.count;
        pendingItems.value = data.items;
    });
```

La ruta está en `web.php` (grupo `auth`):

```php
Route::get('/api/pending-comments', function () {
    $pending = PendingComment::with('instrument')
        ->where('user_id', auth()->id())
        ->where('has_commented', false)
        ->get();
    return response()->json([
        'count' => $pending->count(),
        'items' => $pending,
    ]);
})->name('api.pending-comments');
```

**Justificación:** Se usa `fetch()` nativo en lugar de Inertia porque es una llamada API ligera que devuelve JSON. No necesita renderizar una página completa.

### 7.3 Endpoint getRating (API)

```php
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
```

Sección "Mejor valorados" en la portada (`Welcome.vue`) que muestra 4 productos con sus estrellas y contador.

---

## 8. Sistema de Descuentos Generales

### 8.1 Aplicar descuento

**Archivo:** `app/Http/Controllers/GeneralDiscountController.php`

```php
public function apply(Request $request)
{
    $validated = $request->validate([
        'porcentaje' => 'required|numeric|min:1|max:100',
        'nombre' => 'required|string|max:255',
    ]);

    DB::transaction(function () use ($validated) {
        GeneralDiscount::create([
            'nombre' => $validated['nombre'],
            'porcentaje' => $validated['porcentaje'],
            'activo' => true,
        ]);

        Instrument::whereNotNull('id')->each(function ($instrument) use ($validated) {
            $instrument->update([
                'precio_original' => $instrument->precio,
                'precio' => round($instrument->precio * (1 - $validated['porcentaje'] / 100), 2),
                'descuento_general_applied' => true,
            ]);
        });
    });

    return back()->with('success', 'Descuento aplicado correctamente');
}
```

**Quitar descuento:**

```php
public function remove()
{
    DB::transaction(function () {
        Instrument::where('descuento_general_applied', true)->each(function ($instrument) {
            $instrument->update([
                'precio' => $instrument->precio_original,
                'precio_original' => null,
                'descuento_general_applied' => false,
            ]);
        });
        GeneralDiscount::where('activo', true)->update(['activo' => false]);
    });
}
```

**Justificación:** Se usa `DB::transaction` para atomicidad. Se guarda `precio_original` para poder restaurar precios sin necesidad de almacenar el porcentaje de descuento en cada producto.

---

## 9. Dashboard de Administrador

**Archivo:** `routes/web.php` (closure en ruta `/admin/dashboard`)

Los datos agregados se obtienen con Eloquent:

```php
$topProducts = OrderItem::selectRaw('instrument_id, SUM(cantidad) as total_vendido')
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
```

**Justificación:** Usamos closures en rutas (no controladores dedicados) para estas operaciones simples porque son consultas directas a la BD que devuelven datos agregados. No hay lógica de negocio que justifique un controlador separado.

---

## 10. CRUD de Instrumentos

### 10.1 Controller

**Archivo:** `app/Http/Controllers/InstrumentController.php`

```php
class InstrumentController extends Controller
{
    public function index()
    {
        $instruments = Instrument::with('category', 'subcategory')
            ->withCount('orderItems')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Admin/Instruments/Index', [
            'instruments' => $instruments,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'marca' => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'tipo' => 'required|in:nuevo,usado',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'imagen' => 'nullable|image|max:2048',
            'descripcion' => 'nullable|string',
        ]);

        if ($request->hasFile('imagen')) {
            $validated['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        Instrument::create($validated);
        return redirect()->route('admin.instrumentos.index')
            ->with('success', 'Producto creado correctamente');
    }
}
```

### 10.2 Subcategorías dependientes

El selector de subcategorías se filtra según la categoría seleccionada:

```javascript
const filteredSubcategories = computed(() => {
    return subcategories.filter(s => s.category_id === form.category_id);
});
```

### 10.3 Máquina de estados de pedidos

```php
private const VALID_TRANSITIONS = [
    'Pendiente' => ['Pagado', 'Cancelado'],
    'Pagado'    => ['Enviado', 'Cancelado'],
    'Enviado'   => ['Entregado', 'Cancelado'],
    'Entregado' => [],
    'Cancelado' => [],
];

public function updateStatus(Request $request, Order $order)
{
    $request->validate(['estado' => 'required|string']);
    $newStatus = $request->estado;
    if (!in_array($newStatus, self::VALID_TRANSITIONS[$order->estado] ?? [])) {
        return back()->with('error', 'Transición de estado no válida');
    }
    $order->update(['estado' => $newStatus]);
    return back()->with('success', 'Estado actualizado');
}
```

**Justificación:** La máquina de estados evita transiciones inválidas (ej: pasar de "Pendiente" a "Entregado" directamente). Es un patrón de diseño que garantiza la integridad del flujo de trabajo.

---

## 11. Gráfico Canvas

### 11.1 Frontend (Canvas API nativa)

```javascript
const drawChart = (canvasId, data) => {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    const max = Math.max(...data.map(d => d.value), 1);
    const barWidth = Math.min(40, (canvas.width - 60) / data.length / 2);

    data.forEach((item, i) => {
        const barHeight = (item.value / max) * (canvas.height - 40);
        const x = 30 + i * (barWidth * 2 + 10);
        const y = canvas.height - 20 - barHeight;

        const gradient = ctx.createLinearGradient(x, y, x, y + barHeight);
        gradient.addColorStop(0, '#E87F24');
        gradient.addColorStop(1, '#73A5CA');
        ctx.fillStyle = gradient;
        ctx.fillRect(x, y, barWidth, barHeight);

        // Etiqueta del producto (rotada 45°)
        ctx.save();
        ctx.translate(x + barWidth / 2, canvas.height - 10);
        ctx.rotate(-Math.PI / 4);
        ctx.fillStyle = '#1a1a1a';
        ctx.font = '10px sans-serif';
        ctx.textAlign = 'right';
        ctx.fillText(item.label, 0, 0);
        ctx.restore();
    });
};
```

**Justificación:** No se usa Chart.js ni ninguna librería externa porque el enunciado requiere explícitamente la API Canvas de HTML5. Esto demuestra conocimiento de la API nativa de dibujo del navegador.

---

## 12. Seeders

### 12.1 Ejecución

```bash
php artisan migrate --seed
# o individualmente:
php artisan db:seed --class=AdminUserSeeder
```

### 12.2 Seeders disponibles

| Seeder | Archivo | Datos que crea |
|--------|---------|----------------|
| `AdminUserSeeder` | `database/seeders/AdminUserSeeder.php` | 1 admin + 2 clientes |
| `CategorySeeder` | `database/seeders/CategorySeeder.php` | 7 categorías |
| `SubcategorySeeder` | `database/seeders/SubcategorySeeder.php` | Subcategorías por categoría |
| `InstrumentSeeder` | `database/seeders/InstrumentSeeder.php` | 31 instrumentos |

### 12.3 Usuarios por defecto

| Rol | Email | Contraseña |
|-----|-------|-----------|
| Admin | `admin@tunely.com` | `12341234` |
| Client | `client@tunely.com` | `12341234` |
| Client | `client2@tunely.com` | `12341234` |

### 12.4 Categorías por defecto

1. Guitarras
2. Bajos
3. Baterías
4. Viento
5. Teclados
6. Percusión
7. Amplificación

---

## 13. Manual de instalación

### Requisitos

- PHP 8.2+
- Composer 2.x
- Node.js 20+ + npm
- SQLite (incluido en PHP) o MySQL

### Pasos

```bash
# 1. Clonar repositorio
git clone <url-repositorio>
cd Tunely

# 2. Instalar dependencias PHP
composer install

# 3. Instalar dependencias Node.js
npm install

# 4. Copiar .env y generar key
copy .env.example .env
php artisan key:generate

# 5. (Opcional) Configurar .env para MySQL
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=tunely
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Migrar y poblar BD
php artisan migrate --seed

# 7. Compilar assets
npm run build

# 8. Enlazar storage (para imágenes subidas)
php artisan storage:link

# 9. Iniciar servidor de desarrollo
php artisan serve
# http://localhost:8000
```

---

## 14. API REST (Pràctica 3)

### 14.1 Objectiu

Crear una API REST amb Laravel per ser consumida des d'una aplicació Ionic. L'API exposa endpoints per a productes (instruments), categories i subcategories.

### 14.2 Controladors API

**Ubicació:** `app/Http/Controllers/API/`

**ProductController.php:**

```php
class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        $products = Instrument::with('subcategory')
            ->orderBy('marca')->orderBy('modelo')->get()
            ->map(fn($i) => $this->formatProduct($i));
        return response()->json($products);
    }

    public function bySubcategory(int $subcategoryId): JsonResponse
    {
        $products = Instrument::with('subcategory')
            ->where('subcategory_id', $subcategoryId)
            ->orderBy('marca')->orderBy('modelo')->get()
            ->map(fn($i) => $this->formatProduct($i));
        return response()->json($products);
    }

    public function show(int $id): JsonResponse
    {
        $instrument = Instrument::with('category', 'subcategory')->findOrFail($id);
        return response()->json($this->formatProduct($instrument));
    }

    private function formatProduct(Instrument $instrument): array
    {
        return [
            'id' => $instrument->id,
            'name' => $instrument->marca . ' ' . $instrument->modelo,
            'description' => $instrument->descripcion,
            'price' => number_format($instrument->precio, 2, '.', ''),
            'stock' => $instrument->stock,
            'image' => $instrument->imagen ? asset($instrument->imagen) : null,
            'subcategory_id' => $instrument->subcategory_id,
            'category_id' => $instrument->category_id,
            'created_at' => $instrument->created_at,
            'updated_at' => $instrument->updated_at,
        ];
    }
}
```

**CategoryController.php:**

```php
class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::with('subcategories')
            ->orderBy('nombre')->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->nombre,
                'slug' => $c->slug,
                'subcategories' => $c->subcategories->map(fn($s) => [
                    'id' => $s->id,
                    'name' => $s->nombre,
                    'slug' => $s->slug,
                ]),
            ]);
        return response()->json($categories);
    }
}
```

**Decisió tècnica:** El mètode `formatProduct()` transforma les dades del model `Instrument` al format esperat per l'app Ionic. El camp `name` es construeix concatenant `marca + modelo`. El camp `image` utilitza `asset()` per generar la URL completa del servidor Laravel, evitant errors de rutes relatives des de l'app Ionic.

### 14.3 Rutes API

**Fitxer:** `routes/api.php`

```php
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/subcategory/{subcategoryId}', [ProductController::class, 'bySubcategory']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);
```

**Exemple de resposta JSON (`GET /api/products`):**

```json
[
  {
    "id": 1,
    "name": "Fender Stratocaster Player",
    "description": "Guitarra elèctrica Stratocaster de gama media...",
    "price": "899.00",
    "stock": 24,
    "image": "http://127.0.0.1:8000/img/prod/fender-stratocaster-player.webp",
    "subcategory_id": 1,
    "category_id": 1
  }
]
```

### 14.4 Configuració CORS

**Fitxer:** `config/cors.php`

```php
'allowed_origins' => [
    env('FRONTEND_URL', 'http://localhost:3000'),
    'http://localhost:8100',
],
```

**Fitxer:** `bootstrap/app.php`

S'ha afegit el middleware `HandleCors` al grup de middleware `api` i s'ha habilitat el routing API:

```php
->withRouting(
    web: __DIR__.'/../routes/web.php',
    api: __DIR__.'/../routes/api.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
)
->withMiddleware(function (Middleware $middleware): void {
    $middleware->api(prepend: [
        \Illuminate\Http\Middleware\HandleCors::class,
    ]);
    $middleware->statefulApi();
})
```

**Decisió tècnica:** El CORS es configura explícitament per permetre sol·licituds des de `localhost:8100` (domini de l'app Ionic en desenvolupament). El middleware `HandleCors` s'afegeix al grup `api` per assegurar que totes les respostes API incloguin els headers CORS necessaris.

---

## 15. App Ionic (Pràctica 3)

### 15.1 Objectiu

Crear una aplicació mòbil amb Ionic + Vue 3 (plantilla Tabs) que consumisca l'API REST de Laravel per mostrar categories, subcategories i productes.

### 15.2 Creació del projecte

```bash
ionic start ionospractica tabs --type=vue --no-git --no-link
```

**Plantilla escollida:** `tabs` — proporciona navegació amb pestanyes inferiors, ideal per a l'estructura demanada (Categories, Productes, Info).

### 15.3 Estructura de fitxers

```
ionospractica/src/
├── components/
│   ├── ExploreContainer.vue      # Component per defecte d'Ionic
│   └── ProductList.vue           # Component reutilitzable de llistat de productes
├── router/
│   └── index.ts                  # Configuració de rutes
├── views/
│   ├── TabCategories.vue         # Pestanya 1: Categories i subcategories
│   ├── TabProducts.vue           # Pestanya 2: Llistat de productes
│   ├── Tab3Page.vue              # Pestanya 3: Info placeholder
│   ├── ProductDetails.vue        # Fitxa de producte (fora dels tabs)
│   ├── ProductsBySubcategory.vue # Productes filtrats per subcategoria
│   └── TabsPage.vue              # Layout principal amb pestanyes
├── App.vue
├── main.ts
└── theme/variables.css
```

### 15.4 Router

**Fitxer:** `src/router/index.ts`

```typescript
const routes: Array<RouteRecordRaw> = [
  { path: '/', redirect: '/tabs/tab1' },
  {
    path: '/tabs/',
    component: TabsPage,
    children: [
      { path: '', redirect: '/tabs/tab1' },
      { path: 'tab1', component: () => import('@/views/TabCategories.vue') },
      { path: 'tab2', component: () => import('@/views/TabProducts.vue') },
      { path: 'tab3', component: () => import('@/views/Tab3Page.vue') },
    ],
  },
  { path: '/products/:id', component: () => import('@/views/ProductDetails.vue') },
  { path: '/products/subcategory/:id', component: () => import('@/views/ProductsBySubcategory.vue') },
];
```

**Decisió tècnica:** Les vistes de detall (`ProductDetails`, `ProductsBySubcategory`) es defineixen com a rutes independents (fora dels tabs) per poder tenir el seu propi header amb botó "Tornar" i títol dinàmic, sense interferir amb la navegació per pestanyes.

### 15.5 Component reutilitzable ProductList

**Fitxer:** `src/components/ProductList.vue`

```vue
<template>
  <ion-list v-if="products.length > 0">
    <ion-item v-for="product in products" :key="product.id"
              :router-link="`/products/${product.id}`" detail>
      <ion-thumbnail slot="start">
        <img :src="product.image" :alt="product.name" />
      </ion-thumbnail>
      <ion-label>
        <h2>{{ product.name }}</h2>
        <p>{{ product.price }} €</p>
      </ion-label>
    </ion-item>
  </ion-list>
  <div v-else class="ion-text-center ion-padding">
    <p>No hi ha productes disponibles.</p>
  </div>
</template>

<script setup lang="ts">
defineProps<{ products: Product[] }>();
</script>
```

**Decisió tècnica:** Es defineix com a component `reutilitzable` que accepta un array de productes via `props`. S'utilitza tant a `TabProducts.vue` (tots els productes) com a `ProductsBySubcategory.vue` (productes filtrats), evitant duplicar codi de renderització.

### 15.6 Consum de l'API des d'Ionic

Totes les vistes fan fetch a l'API de Laravel usant l'API nativa `fetch()` de JavaScript dins del hook `onMounted`:

```typescript
onMounted(async () => {
  const res = await fetch('http://127.0.0.1:8000/api/products');
  products.value = await res.json();
});
```

**Decisió tècnica:** S'usa `fetch()` natiu en lloc d'Axios per simplicitat (no cal instal·lar dependències addicionals). Les peticions són GET sense autenticació (l'API és pública per a esta pràctica).

### 15.7 Navegació amb títols dinàmics i botó "Tornar"

```vue
<ion-header>
  <ion-toolbar>
    <ion-buttons slot="start">
      <ion-back-button default-href="/tabs/tab2" text="Tornar" />
    </ion-buttons>
    <ion-title>{{ product?.name || 'Producte' }}</ion-title>
  </ion-toolbar>
</ion-header>
```

**Funcionament:** El component `<ion-back-button>` d'Ionic mostra automàticament una fletxa enrere. Quan es navega des d'una pestanya, torna a eixa pestanya (`default-href`). El títol es canvia dinàmicament des del `<script setup>`.

### 15.8 Icones de pestanyes

```vue
<ion-tab-button tab="tab1" href="/tabs/tab1">
  <ion-icon :icon="cubeOutline" />
  <ion-label>Categories</ion-label>
</ion-tab-button>
<ion-tab-button tab="tab2" href="/tabs/tab2">
  <ion-icon :icon="musicalNotesOutline" />
  <ion-label>Productes</ion-label>
</ion-tab-button>
<ion-tab-button tab="tab3" href="/tabs/tab3">
  <ion-icon :icon="informationCircleOutline" />
  <ion-label>Info</ion-label>
</ion-tab-button>
```

Icones d'Ionicons utilitzades: `cube-outline` (Categories), `musical-notes-outline` (Productes), `information-circle-outline` (Info).

---

## 16. Tests d'errors (Pràctica 3)

### 16.1 Proves funcionals de l'API

| # | Prova | Resultat esperat | Resultat obtingut | Captura |
|---|-------|-------------------|-------------------|---------|
| 1 | `GET /api/products` | Retorna JSON amb tots els productes | ✅ Correcte (30 productes) | ![Test API products](posar_aqui_test_api_products.png) |
| 2 | `GET /api/products/1` | Retorna producte amb id=1 | ✅ Correcte | ![Test API product detail](posar_aqui_test_api_product_detail.png) |
| 3 | `GET /api/products/subcategory/1` | Retorna 4 productes de subcategoria 1 | ✅ Correcte | ![Test API subcategory](posar_aqui_test_api_subcategory.png) |
| 4 | `GET /api/products/999` | Error 404 | ✅ Correcte (Not Found) | ![Test API 404](posar_aqui_test_api_404.png) |
| 5 | `GET /api/categories` | Retorna 5 categories amb subcategories | ✅ Correcte | ![Test API categories](posar_aqui_test_api_categories.png) |
| 6 | `OPTIONS /api/products` | Headers CORS correctes | ✅ Correcte (Vary: Origin) | ![Test CORS](posar_aqui_test_cors.png) |

### 16.2 Proves de l'app Ionic

| # | Prova | Resultat esperat | Resultat obtingut | Captura |
|---|-------|-------------------|-------------------|---------|
| 1 | Obrir app → Tab Categories | Es carreguen categories i subcategories | ✅ Correcte | ![Test Tab1](posar_aqui_test_tab1.png) |
| 2 | Clic a subcategoria | Navega a productes filtrats | ✅ Correcte | ![Test subcategory nav](posar_aqui_test_subcat_nav.png) |
| 3 | Tab Products → carregar productes | Llistat complet ordenat A-Z | ✅ Correcte | ![Test Tab2](posar_aqui_test_tab2.png) |
| 4 | Clic a producte | Navega a ProductDetails amb dades | ✅ Correcte | ![Test product detail](posar_aqui_test_detail.png) |
| 5 | Botó "Tornar" a ProductDetails | Torna al llistat de productes | ✅ Correcte | ![Test back button](posar_aqui_test_back.png) |
| 6 | Tab Info | Es mostra informació estàtica | ✅ Correcte | ![Test Tab3](posar_aqui_test_tab3.png) |
| 7 | Títol dinàmic a ProductDetails | Mostra nom del producte | ✅ Correcte | ![Test title](posar_aqui_test_title.png) |

### 16.3 Proves de connectivitat i CORS

| # | Prova | Resultat | Captura |
|---|-------|----------|---------|
| 1 | API Laravel en localhost:8000 | ✅ Respon correctament | ![Test server running](posar_aqui_test_server.png) |
| 2 | App Ionic en localhost:8100 | ✅ Respon correctament | ![Test ionic serve](posar_aqui_test_ionic.png) |
| 3 | Petició CORS des d'Ionic a Laravel | ✅ Headers CORS correctes | ![Test CORS request](posar_aqui_test_cors_request.png) |

### 16.4 Errors detectats i correccions

| Error | Causa | Solució |
|-------|-------|---------|
| Imatges no es carregaven a Ionic | URL relativa `/img/prod/...` des de localhost:8100 | Afegir `asset()` al controlador API per generar URL completa |
| `ion-img` mostrava la imatge com a fons de pàgina | Component `ion-img` no compatible amb `object-fit` | Substituir per `<img>` natiu amb wrapper centrat |
| CORS bloquejava peticions des d'Ionic | `allowed_origins` no incloïa localhost:8100 | Afegir `http://localhost:8100` a `config/cors.php` |

---

**Fin de la documentación técnica**
