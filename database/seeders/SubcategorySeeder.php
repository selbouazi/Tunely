<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubcategorySeeder extends Seeder
{
    public function run(): void
    {
        $subcategories = [
            ['category_id' => 1, 'nombre' => 'Guitarras eléctricas', 'slug' => 'guitarras-electricas'],
            ['category_id' => 1, 'nombre' => 'Guitarras acústicas', 'slug' => 'guitarras-acusticas'],
            ['category_id' => 1, 'nombre' => 'Bajos', 'slug' => 'bajos'],
            ['category_id' => 2, 'nombre' => 'Viento madera', 'slug' => 'viento-madera'],
            ['category_id' => 2, 'nombre' => 'Viento metal', 'slug' => 'viento-metal'],
            ['category_id' => 3, 'nombre' => 'Baterías acústicas', 'slug' => 'baterias-acusticas'],
            ['category_id' => 3, 'nombre' => 'Baterías electrónicas', 'slug' => 'baterias-electronicas'],
            ['category_id' => 3, 'nombre' => 'Percusión menor', 'slug' => 'percusion-menor'],
            ['category_id' => 4, 'nombre' => 'Pianos digitales', 'slug' => 'pianos-digitales'],
            ['category_id' => 4, 'nombre' => 'Teclados portátiles', 'slug' => 'teclados-portatiles'],
            ['category_id' => 5, 'nombre' => 'Sintetizadores', 'slug' => 'sintetizadores'],
            ['category_id' => 5, 'nombre' => 'Equipo de DJ', 'slug' => 'equipo-dj'],
            ['category_id' => 5, 'nombre' => 'Audio profesionales', 'slug' => 'audio-profesionales'],
        ];

        foreach ($subcategories as $sub) {
            DB::table('subcategories')->insert($sub);
        }
    }
}
