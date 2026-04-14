<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Database\Factories\CategoryFactory;
use Database\Factories\VideoFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

        $category = CategoryFactory::new()
            ->createOne();

        VideoFactory::new()
            ->setCategory($category->id)
            ->setUser($user->id)
            ->withThumbnail()
            ->withVideo()
            ->createMany(5);
    }
}
