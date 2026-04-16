<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstrumentSeeder extends Seeder
{
    public function run(): void
    {
        $imagen = 'https://images.unsplash.com/photo-1510915361894-db8b64506d60?w=500';

        $instruments = [
            [
                'marca' => 'Fender',
                'modelo' => 'Stratocaster Player',
                'tipo' => 'nuevo',
                'precio' => 899.00,
                'precio_original' => null,
                'stock' => 5,
                'imagen' => $imagen,
                'descripcion' => 'Guitarra eléctrica Stratocaster de gama media. Cuerpo de aliso, mástil de arce, diapasón de amaranto. Perfecta para rock, blues y jazz.',
                'category_id' => 1,
                'disponible' => true,
            ],
            [
                'marca' => 'Yamaha',
                'modelo' => 'P-45',
                'tipo' => 'nuevo',
                'precio' => 649.00,
                'precio_original' => null,
                'stock' => 3,
                'imagen' => $imagen,
                'descripcion' => 'Piano digital compacto de 88 teclas con acción de martillo. Sonido realista y teclado realista. Ideal para principiantes.',
                'category_id' => 4,
                'disponible' => true,
            ],
            [
                'marca' => 'Roland',
                'modelo' => 'TD-17KVX',
                'tipo' => 'nuevo',
                'precio' => 799.00,
                'precio_original' => null,
                'stock' => 2,
                'imagen' => $imagen,
                'descripcion' => 'Batería electrónica V-Drums de уровень medio. Módulos de sonido avanzados, pads de mesh. Perfecta para practicar en casa.',
                'category_id' => 3,
                'disponible' => true,
            ],
            [
                'marca' => 'Fender',
                'modelo' => 'Acoustic Sienna',
                'tipo' => 'usado',
                'precio' => 350.00,
                'precio_original' => 450.00,
                'stock' => 1,
                'imagen' => $imagen,
                'descripcion' => 'Guitarra acústica usada en buen estado. Cuerpo dreadnought, tapa de pino, fondo y aros de sapeli. Sonido cálido y equilibrado.',
                'category_id' => 1,
                'disponible' => true,
            ],
            [
                'marca' => 'Korg',
                'modelo' => 'Minilogue XD',
                'tipo' => 'nuevo',
                'precio' => 449.00,
                'precio_original' => null,
                'stock' => 4,
                'imagen' => $imagen,
                'descripcion' => 'Sintetizador analógico de 8 voces con osciladores digitales. Polifónico, ideal para producción electrónica y sound design.',
                'category_id' => 5,
                'disponible' => true,
            ],
            [
                'marca' => 'Pearl',
                'modelo' => 'Export EXX',
                'tipo' => 'nuevo',
                'precio' => 499.00,
                'precio_original' => null,
                'stock' => 3,
                'imagen' => $imagen,
                'descripcion' => 'Batería acústica nivel iniciación. Cascos de caoba, herrajes cromados. Incluye soporte, pedales y palillos.',
                'category_id' => 3,
                'disponible' => true,
            ],
            [
                'marca' => 'Casio',
                'modelo' => 'CDP-S110',
                'tipo' => 'nuevo',
                'precio' => 299.00,
                'precio_original' => null,
                'stock' => 6,
                'imagen' => $imagen,
                'descripcion' => 'Piano digital compacto y ligero. 88 teclas con acción de martillo. Incluye pedal y soporte.',
                'category_id' => 4,
                'disponible' => true,
            ],
            [
                'marca' => 'Yamaha',
                'modelo' => 'YBR-125',
                'tipo' => 'usado',
                'precio' => 280.00,
                'precio_original' => 380.00,
                'stock' => 2,
                'imagen' => $imagen,
                'descripcion' => 'Guitarra semisólida usada. Cuerpo de ontillo, pastillas P-90. Sonido blues clásico. Muy buen estado.',
                'category_id' => 1,
                'disponible' => true,
            ],
        ];

        foreach ($instruments as $instrument) {
            DB::table('instruments')->insert($instrument);
        }
    }
}
