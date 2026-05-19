<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            SubcategorySeeder::class,
            InstrumentSeeder::class,
            AdminUserSeeder::class,
            FaqSeeder::class,
            ContactInfoSeeder::class,
        ]);
    }
}
