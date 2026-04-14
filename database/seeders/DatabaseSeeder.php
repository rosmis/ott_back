<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\VideoStatus;
use App\Models\Category;
use App\Models\User;
use Database\Factories\CategoryFactory;
use Database\Factories\VideoFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $categories = CategoryFactory::new()
            ->createMany(3);

        VideoFactory::new()
            ->setUser($user->id)
            ->withThumbnail()
            ->withVideo()
            ->sequence(static function (Sequence $sequence) use ($categories): array {
                /** @var Category $category */
                $category = $categories->get($sequence->index)
                    ?? $categories->random();

                return [
                    'category_id' => $category->id,
                    'status' => match ($sequence->index) {
                        0 => VideoStatus::Draft,
                        1 => VideoStatus::Archived,
                        default => VideoStatus::Published,
                    },
                ];
            })
            ->createMany(10);
    }
}
