<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Queries\VideoQuery;
use Sylarele\HttpQueryConfig\Http\QueryRequest;

/**
 * @extends QueryRequest<VideoQuery>
 */
class IndexVideoRequest extends QueryRequest
{
    protected function getQuery(): string
    {
        return VideoQuery::class;
    }
}
