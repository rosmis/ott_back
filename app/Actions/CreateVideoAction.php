<?php

declare(strict_types=1);

namespace App\Actions;

use App\Dto\CreateVideoDto;
use App\Enums\VideoStatus;
use App\Models\User;
use App\Models\Video;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

readonly class CreateVideoAction
{
    public function __invoke(CreateVideoDto $dto, User $user): Video
    {
        $video = new Video();
        $video->title = $dto->title;
        $video->slug = Str::slug($dto->title);
        $video->category_id = $dto->categoryId;
        $video->created_by_id = $user->id;
        $video->description = $dto->description;
        $video->status = $dto->status;

        if ($video->status === VideoStatus::Published) {
            $video->published_at = Carbon::now();
        }

        $video->save();

        return $video;
    }
}
