<?php

declare(strict_types=1);

namespace App\Queries;

use App\Builders\VideoBuilder;
use App\Models\Video;
use Sylarele\HttpQueryConfig\Query\Query;
use Sylarele\HttpQueryConfig\Query\QueryConfig;

/**
 * @extends Query<Video, VideoBuilder>
 */
class VideoQuery extends Query
{
    protected function model(): string
    {
        return Video::class;
    }

    protected function configure(QueryConfig $config): void
    {
    }
}
