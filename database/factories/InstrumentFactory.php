<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstrumentFactory extends Factory
{
    public function definition(): array
    {
        $marca = fake()->randomElement(['Fender', 'Gibson', 'Yamaha', 'Roland', 'Korg', 'Pearl', 'Casio', 'Yamaha', 'Alvarez']);
        $modelo = fake()->numerify('Model-###');

        return [
            'marca' => $marca,
            'modelo' => $modelo,
            'tipo' => fake()->randomElement(['nuevo', 'usado']),
            'precio' => fake()->randomFloat(2, 100, 2000),
            'precio_original' => fake()->boolean(30) ? fake()->randomFloat(2, 200, 2500) : null,
            'stock' => fake()->numberBetween(0, 10),
            'imagen' => 'https://images.unsplash.com/photo-1510915361894-db8b64506d60?w=500',
            'descripcion' => fake()->sentence(10),
            'category_id' => Category::factory(),
            'disponible' => true,
        ];
    }
}
