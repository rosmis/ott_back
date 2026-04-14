<?php

declare(strict_types=1);

namespace App\Services;

use App\Actions\UpdateOrCreateVideoAction;
use App\Actions\SaveVideoAction;
use App\Actions\SaveVideoThumbnailAction;
use App\Dto\UpdateOrCreateVideoDto;
use App\Models\User;
use App\Models\Video;
use App\Queries\VideoQuery;
use Illuminate\Support\Facades\App;
use Sylarele\HttpQueryConfig\Contracts\QueryResult;

readonly class VideoService
{
    /**
     * @return QueryResult<Video>
     */
    public function list(VideoQuery $query): QueryResult
    {
        return Video::query()
            ->configureForQuery($query)
            ->paginateForQuery($query);
    }

    public function updateOrCreate(
        UpdateOrCreateVideoDto $dto,
        User $user,
        ?int $video_id = null
    ): void {
        $video = App::call(UpdateOrCreateVideoAction::class, [
            'dto' => $dto,
            'user' => $user,
            'videoId' => $video_id,
        ]);

        App::call(SaveVideoAction::class, [
            'dto' => $dto,
            'video' => $video,
        ]);

        App::call(SaveVideoThumbnailAction::class, [
            'dto' => $dto,
            'video' => $video,
        ]);
    }
}
