<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //add faker for title,author,description
            'title' => $this->faker->sentence(3),
            'author' => $this->faker->name,
            //add image url
            'image' => $this->faker->imageUrl(640, 480, 'books', true),
            'description' => $this->faker->paragraph(3),

        ];
    }
}
