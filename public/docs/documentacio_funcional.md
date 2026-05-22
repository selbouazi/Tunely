# Documentación Funcional - Tunely

**Proyecto Transversal DAW2**
**Tienda online de instrumentos musicales**
**Fecha:** Mayo 2026

---

## Índice

1. [Actividad de la empresa](#1-actividad-de-la-empresa)
2. [Guía de estilos e imagen corporativa](#2-guía-de-estilos-e-imagen-corporativa)
3. [Estructura del sitio web](#3-estructura-del-sitio-web)
4. [Funcionalidades para el cliente](#4-funcionalidades-para-el-cliente)
5. [Funcionalidades para el administrador](#5-funcionalidades-para-el-administrador)

---

## 1. Actividad de la empresa

### a) Marca comercial

- **Nombre comercial:** Tunely
- **Eslogan:** "Tu tienda de instrumentos musicales"
- **Actividad:** Venta online de instrumentos musicales, nuevos y de segunda mano.
- **Perspectivas de futuro:** Mercado nacional con posibilidad de expansión internacional. Más de 10 años de experiencia y 500 instrumentos vendidos.
- **Redes sociales:** Perfiles disponibles en Instagram, Facebook y TikTok (@tunely). Enlaces en el footer de la web.

### b) Dominio web

- **Dominio elegido:** `tunely.es`
- **Estado:** Disponible (comprobado en fecha de realización del proyecto).

---

## 2. Guía de estilos e imagen corporativa

### a) Paleta de colores

| Color | Código HEX | Uso |
|-------|-----------|-----|
| Crema | `#FEFDDF` | Fondo principal de la web |
| Naranja | `#E87F24` | Botones, acentos, precios |
| Azul | `#73A5CA` | Barra de navegación, footer |
| Oscuro | `#1a1a1a` | Textos principales |
| Amarillo | `#FFC81E` | Hover botones, acentos secundarios |

### b) Tipografía

- **Fuente principal:** system-ui, sans-serif (nativa del sistema para maximizar rendimiento).
- **Pesos utilizados:** normal (400), medium (500), bold (700).
- **Sin fuentes 3D ni decorativas** para garantizar tiempos de carga mínimos.

### c) Logotipo

- **Versión principal:** `/img/tunely_logo.png`
- **Versión nav:** `/img/tunely_logo2.png` (más grande para la cabecera).

---

## 3. Estructura del sitio web

### Layouts

La aplicación utiliza tres layouts principales:

1. **AppLayout** — Para páginas públicas: Inicio, Catálogo, Quiénes Somos, Contacto, FAQ, Aviso Legal, Privacidad, Condiciones.
2. **AuthenticatedLayout** — Para páginas de usuario autenticado: Dashboard (Mis Pedidos), Perfil.
3. **AdminLayout** — Para el panel de administración.

### Mapa del sitio

```
Inicio (/)
├── Catálogo (/catalogo)
│   └── Detalle producto (/catalogo/{id})
├── Quiénes Somos (/quien-somos)
├── FAQ (/faq)
├── Contacto (/contacto)
├── Aviso Legal (/aviso-legal)
├── Privacidad (/privacidad)
├── Condiciones (/condiciones)
├── Mis Valoraciones (/mis-valoraciones)
├── Dashboard (/dashboard) → Mis pedidos
├── Perfil (/profile)
├── Checkout (/checkout)
│   └── Confirmación (/checkout/success/{order})
└── Admin (/admin/dashboard)
    ├── Productos (/admin/productos)
    ├── Categorías (/admin/categorias)
    ├── Subcategorías (/admin/subcategorias)
    ├── Pedidos (/admin/pedidos)
    ├── Opiniones (/admin/opiniones)
    ├── Mensajes (/admin/mensajes)
    ├── Usuarios (/admin/usuarios)
    └── Descuento general (/admin/descuento-general)
```

---

## 4. Funcionalidades para el cliente

### 4.1 Registro de usuario

El formulario de registro cumple los siguientes requisitos:

| Campo | Validación |
|-------|-----------|
| Nombre | Mínimo 1 nombre. Sin números ni caracteres especiales. Auto-capitaliza primera letra. Indicador visual verde/rojo. |
| Primer apellido | Sin números ni caracteres especiales. Auto-capitaliza. |
| Segundo apellido | Opcional. Sin números ni caracteres especiales. |
| Fecha de nacimiento | Formato DD/MM/AAAA con máscara automática. Mayor de 18 años y menor de 100. |
| Teléfono | Con código internacional (+34, +1, etc.). Formato libre. |
| Dirección envío | Calle, número, ciudad, provincia, código postal (5 dígitos). |
| Dirección facturación | Checkbox "La misma que la de envío". Si se desmarca, campos separados. |
| Instrumento preferido | Selector desplegable (Guitarra, Bajo, Batería, Teclado, Viento, Indiferente). |
| Nivel de experiencia | Selector (Principiante, Intermedio, Avanzado, Profesional). |
| Email | Formato email válido. Único en BD. |
| Contraseña | Mínimo 8 caracteres. Fortaleza medida con `<meter>` (Débil/Media/Fuerte). Confirmación requerida. |
| Indicadores visuales | Focus: anillo naranja. Válido: borde verde. Error: borde rojo. |

**Comportamiento:**
- Todos los campos obligatorios tienen asterisco `*`.
- El botón "Registrarse" solo se habilita cuando el formulario es válido.
- Muestra spinner "Registrando..." durante el envío.
- Los indicadores visuales (verde) solo aparecen después de que el usuario sale del campo (evento blur).

### 4.2 Inicio de sesión

- Formulario email + contraseña.
- Enlace a registro para usuarios nuevos.
- Recordatorio de valoraciones pendientes mediante llamada AJAX al cargar la página (banner amarillo en la parte superior).

### 4.3 Carrito de compra

- Funciona sin necesidad de estar logueado.
- Almacenamiento en LocalStorage (persistente entre sesiones y cierres de navegador).
- Permite modificar cantidades (+/-) desde el propio carrito desplegable.
- Permite eliminar productos individualmente o vaciar todo el carrito.
- Muestra precio total por producto y total global del carrito.
- Acceso directo al checkout.
- Icono del carrito en la cabecera con contador de artículos (badge naranja).

### 4.4 Checkout (Finalizar compra)

- Requiere inicio de sesión. Si no está logueado, redirige al login.
- Muestra lista de artículos con precios antes de finalizar.
- Recoge datos de envío: nombre, dirección, ciudad, provincia, código postal, teléfono.
- Recoge datos de facturación con opción "misma dirección" (checkbox).
- Campos ficticios de tarjeta: número (16 dígitos), caducidad (MM/AA), CVV (3 dígitos). Son solo visuales, no se almacenan en backend.
- Valida stock en servidor antes de crear el pedido.
- Crea el pedido (Order), las líneas (OrderItems), y los registros pendientes de valoración (PendingComment) en una transacción atómica.
- Decrementa el stock automáticamente.
- Redirige a página de confirmación.

### 4.5 Página de confirmación (OrderSuccess)

- Muestra resumen completo del pedido: número, fecha, artículos, direcciones, total.
- **Opciones post-compra para valorar cada producto:**
  - Botón "Valorar [producto]" → enlaza al detalle del producto para dejar valoración.
  - Botón "No valorar" → llama a la API para marcar como comentado permanentemente.
  - Botón "Ahora no" → oculta localmente el banner (no afecta a BD).
- Enlace "Seguir comprando" → vuelve al catálogo.
- Enlace "Mis pedidos" → va al dashboard del usuario.
- Al cargar la página, se limpia el carrito de localStorage.

### 4.6 Catálogo y detalle de producto

- **Catálogo:** Listado de productos con imagen, marca, modelo, precio con IVA, tipo (nuevo/usado).
  - Filtro por categoría (selector desplegable).
  - Botón "Añadir" en cada producto.
  - Productos sin stock NO aparecen en el catálogo público.
- **Detalle del producto (`/catalogo/{id}`):**
  - Imagen del producto.
  - Información completa: marca, modelo, tipo, precio, descripción, IVA.
  - Valoraciones con estrellas (media) + lista de comentarios de usuarios.
  - Formulario de valoración (solo visible si el usuario ha comprado el producto).
  - **Formulario de consulta:**
    - Visible para todos los visitantes.
    - Si el usuario está logueado: los campos nombre y email se ocultan (autorellenados).
    - Si NO está logueado: muestra nombre y email.
    - Textarea con límite de 150 caracteres (con contador).
    - Botón "Enviar" solo visible cuando el formulario es válido.
    - Spinner durante el envío.

### 4.7 Historial de pedidos (Mis Pedidos)

- Targetas de resumen: total de pedidos, gasto total, último pedido.
- Listado de todas las comandas del usuario.
- Cada comanda muestra: ID, fecha, artículos (marca, modelo, cantidad, precio), total.
- Estado con código de colores: Pendiente (amarillo), Pagado (verde), Enviado (azul), Entregado (gris), Cancelado (rojo).

### 4.8 Mis Valoraciones

- Listado de valoraciones realizadas por el usuario.
- Sección de productos pendientes de valorar con enlace directo al producto.
- Muestra estrella, comentario, fecha y producto valorado.

### 4.9 Formulario de contacto

- Campos: nombre, email, asunto, mensaje.
- Validación completa con errores inline.
- Guarda el mensaje en la base de datos.
- Muestra mensaje de confirmación en verde.

### 4.10 FAQ

- Preguntas frecuentes cargadas desde la base de datos (tabla `faqs`).
- Estilo acordeón (pregunta desplegable con animación).

### 4.11 Quiénes Somos

- Página de presentación de la empresa.
- Video de presentación con subtítulos (`/video/videoMarketing.mp4` + VTT).
- Sección de valores y misión de la empresa.

### 4.12 Redes sociales

- Iconos de Instagram, Facebook y TikTok en el footer.
- Enlaces a los perfiles oficiales (@tunely).
- Efecto hover que cambia al color naranja corporativo.

---

## 5. Funcionalidades para el administrador

### 5.1 Dashboard

- **Targetas de resumen:** Total productos, pedidos, usuarios, categorías.
- **Gráfico de barras (Canvas API):** Top 10 productos más vendidos.
  - Implementado con Canvas API nativa de HTML5, sin librerías externas.
  - Barras con degradado de color naranja a azul.
- **Estado del descuento general:** Muestra si hay un descuento activo y cuántos productos afecta.
- **Accesos directos:** Enlaces a todas las secciones de gestión.

### 5.2 Gestión de categorías

- Listado de categorías con nombre.
- Modal para crear nueva categoría.
- Modal para editar categoría existente.
- Eliminación con confirmación.
- Las categorías existentes: Guitarra, Bajo, Batería, Viento, Teclado, Percusión, Amplificación.

### 5.3 Gestión de subcategorías

- Asociadas a categorías mediante selector desplegable.
- CRUD completo con modal.
- Al cambiar la categoría, se filtran las subcategorías existentes.

### 5.4 Gestión de productos

- **Listado:** Tabla con imagen, marca, modelo, categoría, precio, stock, copias vendidas, disponible/sin stock.
  - Productos sin stock aparecen en rojo con etiqueta "Agotado".
  - Filtro por disponibilidad.
  - Opción de ordenar por stock (clic en cabecera de columna).
- **Crear/Editar producto:**
  - Campos: marca, modelo, precio, tipo (nuevo/usado), stock, categoría, subcategoría, imagen (subida), descripción.
  - Validación completa.
- **Desactivar/Activar producto:** Botón que cambia el flag `disponible`.
- **Descuento general:** Aplicar/Quitar descuento porcentual a todos los productos activos.
  - Guarda el precio original para poder restaurarlo.
  - Se ejecuta en una transacción atómica.

### 5.5 Gestión de pedidos

- **Listado:** Todos los pedidos con ID, cliente, fecha, total, estado.
- **Detalle del pedido:**
  - Información del cliente.
  - Artículos comprados (marca, modelo, cantidad, precio).
  - Direcciones de envío y facturación.
  - Historial de cambios de estado.
- **Máquina de estados:**
  - Pendiente → Pagado / Cancelado
  - Pagado → Enviado / Cancelado
  - Enviado → Entregado / Cancelado
  - Entregado → (estado final)
  - Cancelado → (estado final)
  - Transiciones inválidas se rechazan con mensaje de error.
- **Eliminar pedido:** Permite borrar pedidos completos.

### 5.6 Gestión de opiniones

- Listado de todas las valoraciones de usuarios.
- Muestra: usuario, producto, puntuación (estrellas), comentario, fecha.
- Posibilidad de eliminar valoraciones inapropiadas.

### 5.7 Gestión de usuarios

- Listado de usuarios con nombre, email, rol, fecha de registro.
- Edición de rol (cliente ↔ admin).
- Eliminación con confirmación.

### 5.8 Mensajes de contacto

- Listado de consultas recibidas desde el formulario de contacto.
- Muestra: nombre, email, asunto, mensaje, fecha.
- Ordenados por fecha descendente.

---

**Fin de la documentación funcional**
