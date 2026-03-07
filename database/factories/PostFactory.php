<?php

namespace Database\Factories;

use App\Enums\PostType;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $title = $this->faker->unique()->sentence();

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'type' => PostType::BOTH,
            'excerpt' => Str::limit($title, 200),
            'is_featured' => $this->faker->boolean(),
            'published_at' => $this->faker->dateTime(),
            'category_id' => Category::factory(),
            'user_id' => User::factory(),
        ];
    }
}
