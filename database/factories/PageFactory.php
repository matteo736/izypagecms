<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str; // Import corretto
use App\Models\Page;
use \App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Page>
 */
class PageFactory extends Factory
{
    protected $model = Page::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(), // Genera un titolo casuale
            'slug' => Str::slug($this->faker->sentence()), // Crea uno slug basato sul titolo
            'content' => json_encode([
                'sections' => [
                    ['type' => 'p', 'content' => $this->faker->paragraph()],
                    ['type' => 'img', 'url' => $this->faker->imageUrl()],
                    ['type' => 'video', 'url' => $this->faker->url()],
                ],
            ]),
            'author_id' => User::inRandomOrder()->first()->id, // Assegna un autore casuale,
            'status' => $this->faker->randomElement(['draft', 'published']), // Stato casuale
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
