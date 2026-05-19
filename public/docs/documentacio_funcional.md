# Documentació Funcional - Tunely

**Projecte Transversal DAW2**  
**Tenda online d'instruments musicals**  
**Data:** Maig 2026

---

## Índex

1. [Activitat de l'empresa](#1-activitat-de-lempresa)
2. [Guia d'estils i imatge corporativa](#2-guia-destils-i-imatge-corporativa)
3. [Estructura del lloc web](#3-estructura-del-lloc-web)
4. [Funcionalitats per al client](#4-funcionalitats-per-al-client)
5. [Funcionalitats per a l'administrador](#5-funcionalitats-per-a-ladministrador)

---

## 1. Activitat de l'empresa

### a) Marca comercial

- **Nom comercial:** Tunely
- **Eslògan:** "Tu tienda de instrumentos musicales"
- **Activitat:** Venda online d'instruments musicals, nous i de segona mà.
- **Perspectives de futur:** Mercat nacional amb possibilitat d'expansió internacional. Més de 10 anys d'experiència i 500 instruments venuts.
- **Xarxes socials:** Perfils disponibles a Instagram, Facebook i TikTok (@tunely).

### b) Domini web

- **Domini escollit:** `tunely.es`
- **Estat:** Disponible (comprovat en data de realització del projecte).

[CAPTURA: comprovacio_domini_disponible.png]

---

## 2. Guia d'estils i imatge corporativa

### a) Paleta de colors

| Color | Codi HEX | Ús |
|-------|----------|-----|
| Crema | `#FEFDDF` | Fons principal de la web |
| Taronja | `#E87F24` | Botons, accents, preus |
| Blau | `#73A5CA` | Barra de navegació, footer |
| Fosc | `#1a1a1a` | Textos principals |
| Groc | `#FFC81E` | Hover botons, accents secundaris |

[CAPTURA: paleta_colors.png]

### b) Tipografia

- **Font principal:** system-ui, sans-serif (nativa del sistema per maximitzar rendiment).
- **Pesos utilitzats:** normal (400), medium (500), bold (700).
- **Sense fonts 3D ni decoratives** per garantir temps de càrrega mínims.

### c) Logotip

- **Versió principal:** `/img/tunely_logo.png`
- **Versió nav:** `/img/tunely_logo2.png` (més gran per a la capçalera).

[CAPTURA: logo_tunely.png]

---

## 3. Estructura del lloc web

### Layouts

L'aplicació utilitza tres layouts principals:

1. **AppLayout** — Per a pàgines públiques: Inici, Catàleg, Qui som, Contacte, FAQ, Aviso Legal, Privacitat, Condicions.
2. **AuthenticatedLayout** — Per a pàgines d'usuari autenticat: Dashboard (Mis Pedidos), Perfil.
3. **AdminLayout** — Per al panell d'administració.

### Wireframes

#### Portada (Homepage)

[CAPTURA: wireframe_homepage.png]

- Capçalera amb logo + navegació + carret
- Secció hero (presentació + CTA)
- Productes destacats
- Footer amb enllaços legals

#### Catàleg

[CAPTURA: wireframe_catalogo.png]

- Filtre per categories
- Graella de productes (imatge, nom, preu, stock, botons)
- Paginació (quan calgui)

#### Detall de producte

[CAPTURA: wireframe_producte.png]

- Imatge gran
- Informació: marca, model, preu, tipus, stock
- Valoracions amb estrelles + comentaris
- Formulari de valoració (si ha comprat)
- Botó "Añadir al carrito"

#### Checkout

[CAPTURA: wireframe_checkout.png]

- Llistat d'articles del carret
- Formulari d'enviament
- Formulari de facturació (checkbox "mateixa adreça")
- Dades de targeta fictícia
- Resum de compra + botó confirmar

#### Dashboard client (Mis Pedidos)

[CAPTURA: wireframe_mis_pedidos.png]

- Targetes de resum (total pedidos, gasto total, último pedido)
- Llistat de comandes amb estat i detalls

#### Admin Dashboard

[CAPTURA: wireframe_admin_dashboard.png]

- Targetes de resum (productes, pedidos, usuaris, categories)
- Gràfic de barres canvas (productes més venuts)

---

## 4. Funcionalitats per al client

### 4.1 Registre d'usuari

El formulari de registre compleix els següents requisits:

- **Nom i cognoms:** Mínim 1 nom i 1 cognom, màxim 2 noms i 2 cognoms. Sense números ni caràcters especials. La primera lletra de cada mot es converteix a majúscula automàticament.
- **Data de naixement:** Validació de major de 18 anys i menor de 100.
- **Telèfon:** Amb codi internacional.
- **Direcció d'enviament:** Patró vàlid (carrer, número, ciutat, província, CP).
- **Direcció de facturació:** Opció "mateixa que l'enviament".
- **Camps extra:** Instrument preferit, nivell d'experiència.
- **Email:** Validació de format i únic.
- **Contrasenya:** Mínim 8 caràcters, majúscules, minúscules i números. Confirmació. Indicador de fortalesa amb `<meter>`.
- **Validació visual:** Canvi de color al focus/blur, indicador verd/vermell quan els camps són vàlids/invàlids.

[CAPTURA: formulari_registre.png]
[CAPTURA: validacio_password_meter.png]
[CAPTURA: errors_validacio.png]

### 4.2 Inici de sessió

- Formulari email + contrasenya.
- Enllaç a registre per a usuaris nous.
- Recordatori de valoracions pendents mitjançant crida AJAX en carregar la pàgina.

[CAPTURA: formulari_login.png]

### 4.3 Carret de compra

- Funciona sense necessitat d'estar loguejat.
- Emmagatzematge a LocalStorage (persistent entre sessions).
- Permet modificar quantitats (+/-).
- Permet eliminar productes.
- Mostra preu total per producte i total del carret.
- Accés directe al checkout.

[CAPTURA: carret_compra.png]

### 4.4 Checkout (Finalitzar compra)

- Requereix inici de sessió.
- Recull dades d'enviament: nom, adreça, ciutat, província, CP, telèfon.
- Recull dades de facturació (opció "mateixa adreça").
- Camp fictici de targeta: número (16 dígits), caducitat (MM/AA), CVV (3 dígits).
- Valida stock al servidor abans de crear la comanda.
- Crea Order + OrderItems + PendingComment.
- Decrementa stock automàticament.
- Redirigeix a pàgina de confirmació.

[CAPTURA: checkout_formulari.png]
[CAPTURA: checkout_targeta.png]
[CAPTURA: pagina_confirmacio.png]

### 4.5 Catàleg i detall de producte

- Llistat de productes amb imatge, marca, model, preu, tipus, stock.
- Filtre per categoria.
- Pàgina de detall amb informació completa.
- Valoracions amb mitjana d'estrelles i llistat de comentaris.
- Formulari de valoració (només visible si l'usuari ha comprat el producte).

[CAPTURA: catalogo_filtre.png]
[CAPTURA: detall_producte_valoracions.png]

### 4.6 Historial de comandes (Mis Pedidos)

- Llistat de totes les comandes de l'usuari.
- Targetes de resum: total pedidos, gasto total, últim pedido.
- Cada comanda mostra: ID, data, articles, preus, estat (amb codi de colors).
- Estats: Pendiente, Pagado, Enviado, Entregado, Cancelado.

[CAPTURA: mis_pedidos.png]

### 4.7 Formulari de contacte

- Camps: nom, email, assumpte, missatge.
- Validació completa amb errors inline.
- Guarda el missatge a la base de dades.
- Mostra missatge de confirmació en verd.

[CAPTURA: formulari_contacte.png]

### 4.8 FAQ

- Preguntes freqüents carregades des de la base de dades.
- Estil acordió (pregunta desplegable).

[CAPTURA: faq.png]

---

## 5. Funcionalitats per a l'administrador

### 5.1 Dashboard

- Targetes de resum: total productes, pedidos, usuaris, categories.
- Gràfic de barres (Canvas) amb els 10 productes més venuts.

[CAPTURA: admin_dashboard.png]
[CAPTURA: admin_grafic_canvas.png]

### 5.2 Gestió de categories

- Llistat de categories amb modal per crear/editar.
- Eliminació amb confirmació.

[CAPTURA: admin_categories.png]

### 5.3 Gestió de subcategories

- Associades a categories mitjançant selector.
- CRUD complet amb modal.

[CAPTURA: admin_subcategories.png]

### 5.4 Gestió de productes

- Llistat amb filtre per disponibilitat.
- Crear/editar producte: marca, modelo, precio, stock, categoria, subcategoria, imatge, descripció.
- Desactivar/activar producte.
- Descompte global (per percentatge) a tots els productes disponibles.
- Control d'stock: productes amb stock=0 s'amaguen del catàleg.

[CAPTURA: admin_productes_llistat.png]
[CAPTURA: admin_producte_formulari.png]
[CAPTURA: admin_descompte_global.png]

### 5.5 Gestió de comandes

- Llistat de totes les comandes amb estat i dates.
- Detall de comanda: informació client, productes, adreces d'enviament/facturació.
- Canvi d'estat amb màquina d'estats: pendiente → pagado → enviado → entregado (o cancelado en qualsevol punt no final).

[CAPTURA: admin_comandes_llistat.png]
[CAPTURA: admin_comanda_detall.png]
[CAPTURA: admin_canvi_estat.png]

### 5.6 Gestió d'opinions

- Llistat de valoracions d'usuaris.
- Possibilitat d'eliminar valoracions indegudes.

[CAPTURA: admin_opinions.png]

### 5.7 Missatges de contacte

- Llistat de consultes rebudes des del formulari de contacte.
- Mostra nom, email, assumpte i missatge.

[CAPTURA: admin_missatges.png]

---

**Fi de la documentació funcional**
