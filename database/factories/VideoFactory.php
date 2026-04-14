<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\VideoStatus;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @extends Factory<Video>
 */
class VideoFactory extends Factory
{
    private const string VIDEO_THUMBNAIL_PATH = 'videos/%d/thumbnail_%s.png';
    private const string VIDEO_PATH = 'videos/%d/%s.png';

    public function definition(): array
    {
        $videoTitle = $this->faker->sentence();

        return [
            'title' => $videoTitle,
            'slug' => Str::slug($videoTitle),
            'description' => $this->faker->paragraph(),
            'thumbnail_url' => $this->faker->imageUrl(640, 480, 'cats'),
            'duration_seconds' => $this->faker->numberBetween(60, 3600),
            'status' => VideoStatus::Published,
            'published_at' => Carbon::now(),
        ];
    }

    public function setCategory(int $categoryId): self
    {
        return $this->state([
            'category_id' => $categoryId,
        ]);
    }

    public function withThumbnail(): self
    {
        $file = (string) file_get_contents(storage_path('samples/thumbnail.png'));

        return $this->afterCreating(
            static function (Video $video) use ($file): void {
                $filename = sprintf(
                    self::VIDEO_THUMBNAIL_PATH,
                    $video->id,
                    strtolower((string) Str::ulid()),
                );

                Storage::put($filename, $file);

                $video->thumbnail_url = Storage::url($filename);

                $video->save();
            }
        );
    }

    public function withVideo(): self
    {
        $file = (string) file_get_contents(storage_path('samples/video.mp4'));

        return $this->afterCreating(
            static function (Video $video) use ($file): void {
                $filename = sprintf(
                    self::VIDEO_PATH,
                    $video->id,
                    strtolower((string) Str::ulid()),
                );

                Storage::put($filename, $file);

                $video->video_url = Storage::url($filename);

                $video->save();
            }
        );
    }
}
