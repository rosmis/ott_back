<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\VideoStatus;
use App\Models\Category;
use App\Models\User;
use Database\Factories\CategoryFactory;
use Database\Factories\UserFactory;
use Database\Factories\VideoFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $admin = UserFactory::new()
            ->setAdmin()
            ->state([
                'email' => 'admin@example.com',
            ])
            ->createOne();

        $editor = UserFactory::new()
            ->state([
                'email' => 'editor@example.com',
            ])
            ->createOne();

        $categories = CategoryFactory::new()
            ->createMany(3);

        VideoFactory::new()
            ->withThumbnail()
            ->withVideo()
            ->sequence(static function (Sequence $sequence) use (
                $categories,
                $admin,
                $editor
            ): array {
                /** @var Category $category */
                $category = $categories->get($sequence->index)
                    ?? $categories->random();
                /** @var User $randomUser */
                $randomUser = fake()->randomElement([$admin, $editor]);

                return [
                    'created_by_id' => $randomUser->id,
                    'category_id' => $category->id,
                    'status' => match ($sequence->index) {
                        0 => VideoStatus::Draft,
                        1 => VideoStatus::Archived,
                        default => VideoStatus::Published,
                    },
                ];
            })
            ->createMany(20);
    }
}
