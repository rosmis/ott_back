<?php

declare(strict_types=1);

namespace App\Actions;

use App\Dto\UpdateOrCreateVideoDto;
use App\Models\Video;
use getID3;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

readonly class SaveVideoAction
{
    private const string VIDEO_PATH = 'videos/%d';

    public function __construct(
        private getID3 $getID3
    ) {
    }

    public function __invoke(
        UpdateOrCreateVideoDto $dto,
        Video $video
    ): void {
        if (is_string($video->video_url)) {
            Storage::delete($video->video_url);
        }

        $file = $this->getID3->analyze($dto->video->getRealPath());
        $duration = $file['playtime_seconds'];

        $ulid = strtolower((string) Str::ulid());
        $directory = sprintf(self::VIDEO_PATH, $video->id);
        $filename = sprintf('%s.mp4', $ulid);

        $filePath = Storage::putFileAs($directory, $dto->video, $filename);

        $video->video_url = Storage::url($filePath);
        $video->duration_seconds = (int) round($duration);

        $video->save();
    }
}
