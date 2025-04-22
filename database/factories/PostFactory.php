<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str; // Import corretto
use App\Models\Post; // Import corretto
use \App\Models\User;
use \App\Models\Post_Type;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sectionId = 0;
        return [
            'title' => $this->faker->sentence(2), // Genera un titolo casuale
            'slug' => Str::slug($this->faker->sentence(2)), // Crea uno slug basato sul titolo
            'content' => json_encode([
                'sections' => [
                    ['id' => $sectionId++ ,'type' => 'p', 'content' => $this->faker->paragraph()],
                    ['id' => $sectionId++ ,'type' => 'img', 'url' => $this->faker->imageUrl()],
                    ['id' => $sectionId++ ,'type' => 'video', 'url' => $this->faker->url()],
                ],
            ]),
            'author_id' => User::inRandomOrder()->first()->id, // Assegna un autore casuale,
            'post_type_id' => 4, // Assegna un tipo di post casuale
            'status' => $this->faker->randomElement(['draft', 'published']), // Stato casuale
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
