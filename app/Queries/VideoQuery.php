<?php

declare(strict_types=1);

namespace App\Queries;

use App\Builders\VideoBuilder;
use App\Models\Video;
use Sylarele\HttpQueryConfig\Enums\SortOrder;
use Sylarele\HttpQueryConfig\Query\Query;
use Sylarele\HttpQueryConfig\Query\QueryConfig;
use Sylarele\HttpQueryConfig\Query\ScopeArgument;
use Sylarele\HttpQueryConfig\Transformers\IntegerTransformer;

/**
 * @extends Query<Video, VideoBuilder>
 *
 * @see VideoBuilder::
 */
class VideoQuery extends Query
{
    protected function model(): string
    {
        return Video::class;
    }

    protected function configure(QueryConfig $config): void
    {
        $config->filter('status');

        $config->filter('whereCategoryId')
            ->scope()
            ->arg(
                'value',
                static fn (ScopeArgument $arg) => $arg
                    ->withValidation([
                        'required_with:whereCategoryId',
                        'integer',
                    ])
                    ->transform(new IntegerTransformer())
            );

        $config->sorts(
            'id',
            'created_at',
        );

        $config
            ->sort('created_at')
            ->asDefault(SortOrder::Descending);
    }
}
