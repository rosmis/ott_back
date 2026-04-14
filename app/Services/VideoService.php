<?php

declare(strict_types=1);

namespace App\Services;

use App\Actions\CreateVideoAction;
use App\Actions\SaveVideoAction;
use App\Actions\SaveVideoThumbnailAction;
use App\Dto\CreateVideoDto;
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

    public function create(CreateVideoDto $dto, User $user)
    {
        $video = App::call(CreateVideoAction::class, [
            'dto' => $dto,
            'user' => $user,
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
