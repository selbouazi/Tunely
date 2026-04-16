<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Guitarras',
            'Bajos',
            'Baterías',
            'Pianos',
            'Sintetizadores',
            'Vientos',
            'Cuerdas',
            'Percusión',
        ]);

        return [
            'nombre' => $name,
            'slug' => str()->slug($name),
        ];
    }
}
