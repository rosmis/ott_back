<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $categoryName = $this->faker->word();

        return [
            'name' => $categoryName,
            'slug' => Str::slug($categoryName),
            'description' => $this->faker->sentence(),
            'is_published' => true,
        ];
    }
}
