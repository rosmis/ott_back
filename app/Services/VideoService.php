<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Video;
use App\Queries\VideoQuery;
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
}
