<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactInfoSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['label' => 'Dirección', 'value' => 'C/ Gran Via, 123, 08014 Barcelona', 'type' => 'address', 'order' => 0],
            ['label' => 'Teléfono', 'value' => '+34 93 123 45 67', 'type' => 'phone', 'order' => 1],
            ['label' => 'Email', 'value' => 'info@tunely.es', 'type' => 'email', 'order' => 2],
            ['label' => 'Horario', 'value' => 'Lunes - Viernes: 9:00 - 20:00', 'type' => 'hours', 'order' => 3],
        ];

        foreach ($items as $item) {
            DB::table('contact_info')->insert($item);
        }
    }
}
