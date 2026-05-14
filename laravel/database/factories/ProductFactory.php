<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class ProductFactory extends Factory
{
    
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'Coca Cola',
                'Tra sua',
                'Hamburger',
                'Pizza',
                'Ga ran',
                'Banh mi',
                'Ca phe',
                'Matcha',
                'Khoai tay chien'
            ]),
            'price' => fake()->numberBetween(1000, 500000),
            'quantity' => fake()->numberBetween(1, 100),
            'image' => 'default.jpg',
            'category_id' => 1
        ];
    }
}
