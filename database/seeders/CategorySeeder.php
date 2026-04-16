<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['nombre' => 'Cuerda', 'slug' => 'cuerda'],
            ['nombre' => 'Viento', 'slug' => 'viento'],
            ['nombre' => 'Percusión', 'slug' => 'percussion'],
            ['nombre' => 'Teclado', 'slug' => 'teclado'],
            ['nombre' => 'Electrónico', 'slug' => 'electronico'],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert($category);
        }
    }
}
