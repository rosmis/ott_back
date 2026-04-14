<?php

declare(strict_types=1);

namespace App\Actions;

use App\Dto\UpdateOrCreateVideoDto;
use App\Models\Video;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

readonly class SaveVideoThumbnailAction
{
    private const string VIDEO_THUMBNAIL_PATH = 'videos/%d';

    public function __invoke(
        UpdateOrCreateVideoDto $dto,
        Video                  $video
    ): void {
        if (is_string($video->thumbnail_url)) {
            Storage::delete($video->thumbnail_url);
        }

        $ulid = strtolower((string) Str::ulid());
        $directory = sprintf(self::VIDEO_THUMBNAIL_PATH, $video->id);
        $filename = sprintf('thumbnail_%s.mp4', $ulid);

        $filePath = Storage::putFileAs($directory, $dto->thumbnail, $filename);

        $video->thumbnail_url = Storage::url($filePath);

        $video->save();
    }
}
