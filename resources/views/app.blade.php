<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Tunely - Tu tienda de instrumentos musicales">
        <meta name="theme-color" content="#000000">

        <link rel="icon" type="image/png" href="/img/tunely_logo.png">
        <link rel="apple-touch-icon" href="/img/tunely_logo.png">

        <title inertia>{{ config('app.name', 'Tunely') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased bg-gray-950">
        @inertia
    </body>
</html>
