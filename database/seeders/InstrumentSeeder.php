<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InstrumentSeeder extends Seeder
{
    public function run(): void
    {
        $instruments = [
            // ── Cuerda → Guitarras eléctricas (cat 1, sub 1) ──
            ['marca' => 'Fender', 'modelo' => 'Stratocaster Player', 'tipo' => 'nuevo', 'precio' => 899.00, 'precio_original' => null, 'iva' => 21.00, 'stock' => 25, 'descripcion' => 'Guitarra eléctrica Stratocaster de gama media. Cuerpo de aliso, mástil de arce, diapasón de amaranto.', 'category_id' => 1, 'subcategory_id' => 1, 'disponible' => true],
            ['marca' => 'Yamaha', 'modelo' => 'Pacifica 112V', 'tipo' => 'usado', 'precio' => 280.00, 'precio_original' => 380.00, 'stock' => 10, 'descripcion' => 'Guitarra eléctrica usada en buen estado. Cuerpo de aliso, mástil de arce, pastillas HSS.', 'category_id' => 1, 'subcategory_id' => 1, 'disponible' => true],
            ['marca' => 'Gibson', 'modelo' => 'Les Paul Standard', 'tipo' => 'nuevo', 'precio' => 2499.00, 'precio_original' => null, 'iva' => 21.00, 'stock' => 8, 'descripcion' => 'Guitarra eléctrica icónica con cuerpo de caoba y tapa de arce. Pastillas humbucker.', 'category_id' => 1, 'subcategory_id' => 1, 'disponible' => true],
            ['marca' => 'Ibanez', 'modelo' => 'RG550', 'tipo' => 'nuevo', 'precio' => 1099.00, 'precio_original' => null, 'iva' => 21.00, 'stock' => 14, 'descripcion' => 'Guitarra eléctrica superstrat con pastillas DiMarzio. Mástil Wizard, trémolo Edge.', 'category_id' => 1, 'subcategory_id' => 1, 'disponible' => true],
            // ── Cuerda → Guitarras acústicas (cat 1, sub 2) ──
            ['marca' => 'Fender', 'modelo' => 'Acoustic Sienna', 'tipo' => 'usado', 'precio' => 350.00, 'precio_original' => 450.00, 'stock' => 8, 'descripcion' => 'Guitarra acústica usada en buen estado. Cuerpo dreadnought, tapa de pino.', 'category_id' => 1, 'subcategory_id' => 2, 'disponible' => true],
            ['marca' => 'Taylor', 'modelo' => '314ce', 'tipo' => 'nuevo', 'precio' => 1899.00, 'precio_original' => null, 'iva' => 21.00, 'stock' => 6, 'descripcion' => 'Guitarra acústica-electrónica de gama alta. Tapa de abeto, fondo y aros de caoba.', 'category_id' => 1, 'subcategory_id' => 2, 'disponible' => true],
            ['marca' => 'Yamaha', 'modelo' => 'FG830', 'tipo' => 'nuevo', 'precio' => 399.00, 'precio_original' => null, 'iva' => 21.00, 'stock' => 22, 'descripcion' => 'Guitarra acústica dreadnought con tapa de pino macizo. Sonido equilibrado.', 'category_id' => 1, 'subcategory_id' => 2, 'disponible' => true],
            // ── Cuerda → Bajos (cat 1, sub 3) ──
            ['marca' => 'Fender', 'modelo' => 'Precision Bass Player', 'tipo' => 'nuevo', 'precio' => 849.00, 'precio_original' => null, 'iva' => 21.00, 'stock' => 12, 'descripcion' => 'Bajo eléctrico Precision de gama media. Cuerpo de aliso, mástil de arce.', 'category_id' => 1, 'subcategory_id' => 3, 'disponible' => true],
            ['marca' => 'Marcus Miller', 'modelo' => 'V5', 'tipo' => 'nuevo', 'precio' => 749.00, 'precio_original' => null, 'iva' => 21.00, 'stock' => 9, 'descripcion' => 'Bajo activo/pasivo con cuerpo de fresno. Pastillas Nordstrand.', 'category_id' => 1, 'subcategory_id' => 3, 'disponible' => true],
            // ── Viento → Viento madera (cat 2, sub 4) ──
            ['marca' => 'Yamaha', 'modelo' => 'YCL-255', 'tipo' => 'nuevo', 'precio' => 549.00, 'precio_original' => null, 'iva' => 21.00, 'stock' => 15, 'descripcion' => 'Clarinete estudiante en si bemol. Cuerpo de resina ABS, llaves niqueladas.', 'category_id' => 2, 'subcategory_id' => 4, 'disponible' => true],
            ['marca' => 'Jupiter', 'modelo' => 'JFL-700', 'tipo' => 'nuevo', 'precio' => 1299.00, 'precio_original' => null, 'iva' => 21.00, 'stock' => 7, 'descripcion' => 'Flauta travesera plateada con mecanismo offset G.', 'category_id' => 2, 'subcategory_id' => 4, 'disponible' => true],
            ['marca' => 'Selmer', 'modelo' => 'SS600', 'tipo' => 'usado', 'precio' => 2100.00, 'precio_original' => 2600.00, 'stock' => 3, 'descripcion' => 'Saxo alto usado en buen estado. Cuerpo de latón lacado.', 'category_id' => 2, 'subcategory_id' => 4, 'disponible' => true],
            // ── Viento → Viento metal (cat 2, sub 5) ──
            ['marca' => 'Bach', 'modelo' => 'TR-301', 'tipo' => 'nuevo', 'precio' => 499.00, 'precio_original' => null, 'iva' => 21.00, 'stock' => 11, 'descripcion' => 'Trompeta estudiante en si bemol. Campana de latón, pistones monel.', 'category_id' => 2, 'subcategory_id' => 5, 'disponible' => true],
            ['marca' => 'Yamaha', 'modelo' => 'YSL-354', 'tipo' => 'nuevo', 'precio' => 899.00, 'precio_original' => null, 'iva' => 21.00, 'stock' => 5, 'descripcion' => 'Trombón de varas estudiante. Campana 200mm, varas cromadas.', 'category_id' => 2, 'subcategory_id' => 5, 'disponible' => true],
            // ── Percusión → Baterías acústicas (cat 3, sub 6) ──
            ['marca' => 'Pearl', 'modelo' => 'Export EXX', 'tipo' => 'nuevo', 'precio' => 499.00, 'precio_original' => null, 'iva' => 21.00, 'stock' => 15, 'descripcion' => 'Batería acústica nivel iniciación. Cascos de caoba, herrajes cromados.', 'category_id' => 3, 'subcategory_id' => 6, 'disponible' => true],
            ['marca' => 'Tama', 'modelo' => 'Imperialstar', 'tipo' => 'nuevo', 'precio' => 699.00, 'precio_original' => null, 'iva' => 21.00, 'stock' => 10, 'descripcion' => 'Batería acústica completa con herrajes dobles. Cascos de álamo.', 'category_id' => 3, 'subcategory_id' => 6, 'disponible' => true],
            // ── Percusión → Baterías electrónicas (cat 3, sub 7) ──
            ['marca' => 'Roland', 'modelo' => 'TD-17KVX', 'tipo' => 'nuevo', 'precio' => 799.00, 'precio_original' => null, 'iva' => 21.00, 'stock' => 12, 'descripcion' => 'Batería electrónica V-Drums de nivel medio. Módulos de sonido avanzados.', 'category_id' => 3, 'subcategory_id' => 7, 'disponible' => true],
            ['marca' => 'Alesis', 'modelo' => 'Nitro Mesh', 'tipo' => 'nuevo', 'precio' => 399.00, 'precio_original' => null, 'iva' => 21.00, 'stock' => 20, 'descripcion' => 'Batería electrónica compacta con pads de malla. Módulo con 40 kits.', 'category_id' => 3, 'subcategory_id' => 7, 'disponible' => true],
            // ── Percusión → Percusión menor (cat 3, sub 8) ──
            ['marca' => 'LP', 'modelo' => 'Aspire', 'tipo' => 'nuevo', 'precio' => 149.00, 'precio_original' => null, 'iva' => 21.00, 'stock' => 30, 'descripcion' => 'Cajón flamenco nivel iniciación. Cuerpo de abedul, tapa golpeadora.', 'category_id' => 3, 'subcategory_id' => 8, 'disponible' => true],
            ['marca' => 'Meinl', 'modelo' => 'Timbales Set', 'tipo' => 'nuevo', 'precio' => 269.00, 'precio_original' => null, 'iva' => 21.00, 'stock' => 8, 'descripcion' => 'Set de timbales de 13 y 14 pulgadas. Cascos de acero.', 'category_id' => 3, 'subcategory_id' => 8, 'disponible' => true],
            // ── Teclado → Pianos digitales (cat 4, sub 9) ──
            ['marca' => 'Yamaha', 'modelo' => 'P-45', 'tipo' => 'nuevo', 'precio' => 649.00, 'precio_original' => null, 'iva' => 21.00, 'stock' => 18, 'descripcion' => 'Piano digital compacto de 88 teclas con acción de martillo.', 'category_id' => 4, 'subcategory_id' => 9, 'disponible' => true],
            ['marca' => 'Casio', 'modelo' => 'CDP-S110', 'tipo' => 'nuevo', 'precio' => 299.00, 'precio_original' => null, 'iva' => 21.00, 'stock' => 30, 'descripcion' => 'Piano digital compacto y ligero. 88 teclas con acción de martillo.', 'category_id' => 4, 'subcategory_id' => 9, 'disponible' => true],
            ['marca' => 'Roland', 'modelo' => 'FP-30X', 'tipo' => 'nuevo', 'precio' => 899.00, 'precio_original' => null, 'iva' => 21.00, 'stock' => 14, 'descripcion' => 'Piano digital de 88 teclas con sonido SuperNATURAL. Bluetooth.', 'category_id' => 4, 'subcategory_id' => 9, 'disponible' => true],
            // ── Teclado → Teclados portátiles (cat 4, sub 10) ──
            ['marca' => 'Yamaha', 'modelo' => 'PSR-E373', 'tipo' => 'nuevo', 'precio' => 199.00, 'precio_original' => null, 'iva' => 21.00, 'stock' => 40, 'descripcion' => 'Teclado portátil de 61 teclas con 622 sonidos. Estilos de acompañamiento.', 'category_id' => 4, 'subcategory_id' => 10, 'disponible' => true],
            ['marca' => 'Casio', 'modelo' => 'CT-S100', 'tipo' => 'usado', 'precio' => 100.00, 'precio_original' => 150.00, 'stock' => 6, 'descripcion' => 'Teclado portátil usado. 61 teclas, sonidos integrados.', 'category_id' => 4, 'subcategory_id' => 10, 'disponible' => true],
            // ── Electrónico → Sintetizadores (cat 5, sub 11) ──
            ['marca' => 'Korg', 'modelo' => 'Minilogue XD', 'tipo' => 'nuevo', 'precio' => 449.00, 'precio_original' => null, 'iva' => 21.00, 'stock' => 20, 'descripcion' => 'Sintetizador analógico de 8 voces con osciladores digitales.', 'category_id' => 5, 'subcategory_id' => 11, 'disponible' => true],
            ['marca' => 'Moog', 'modelo' => 'Mother-32', 'tipo' => 'nuevo', 'precio' => 699.00, 'precio_original' => null, 'iva' => 21.00, 'stock' => 5, 'descripcion' => 'Sintetizador analógico semi-modular. Filtro ladder Moog.', 'category_id' => 5, 'subcategory_id' => 11, 'disponible' => true],
            // ── Electrónico → Equipo de DJ (cat 5, sub 12) ──
            ['marca' => 'Pioneer', 'modelo' => 'DDJ-400', 'tipo' => 'nuevo', 'precio' => 299.00, 'precio_original' => null, 'iva' => 21.00, 'stock' => 16, 'descripcion' => 'Controlador DJ para Rekordbox. 2 canales, pads de rendimiento.', 'category_id' => 5, 'subcategory_id' => 12, 'disponible' => true],
            ['marca' => 'Numark', 'modelo' => 'Mixtrack Platinum FX', 'tipo' => 'nuevo', 'precio' => 249.00, 'precio_original' => null, 'iva' => 21.00, 'stock' => 13, 'descripcion' => 'Controlador DJ de 4 canales con pantallas integradas. Compatible con Serato.', 'category_id' => 5, 'subcategory_id' => 12, 'disponible' => true],
            // ── Electrónico → Audio profesionales (cat 5, sub 13) ──
            ['marca' => 'Shure', 'modelo' => 'SM57', 'tipo' => 'nuevo', 'precio' => 129.00, 'precio_original' => null, 'iva' => 21.00, 'stock' => 35, 'descripcion' => 'Micrófono dinámico cardiode profesional. Ideal para instrumentos y voces.', 'category_id' => 5, 'subcategory_id' => 13, 'disponible' => true],
            ['marca' => 'Focusrite', 'modelo' => 'Scarlett 2i2', 'tipo' => 'nuevo', 'precio' => 179.00, 'precio_original' => null, 'iva' => 21.00, 'stock' => 28, 'descripcion' => 'Interfaz de audio USB de 2 entradas. Preamplificadores de alta calidad.', 'category_id' => 5, 'subcategory_id' => 13, 'disponible' => true],
        ];

        $source = public_path('img/carrusel/webp.webp');

        foreach ($instruments as &$instrument) {
            $slug = Str::slug($instrument['marca'] . '-' . $instrument['modelo']);
            $filename = '/img/prod/' . $slug . '.webp';
            $instrument['imagen'] = $filename;

            $dest = public_path($filename);
            if (!file_exists($dest)) {
                copy($source, $dest);
            }
        }
        unset($instrument);

        foreach ($instruments as $instrument) {
            DB::table('instruments')->insert($instrument);
        }
    }
}
