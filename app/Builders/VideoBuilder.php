<?php

declare(strict_types=1);

namespace App\Builders;

use App\Models\Video;
use Illuminate\Database\Eloquent\Builder;
use Sylarele\HttpQueryConfig\Concerns\HttpBuilder;

/**
 * @template TModelClass of Video
 *
 * @extends Builder<TModelClass>
 */
final class VideoBuilder extends Builder
{
    /** @use HttpBuilder<TModelClass> */
    use HttpBuilder;

    public function whereCategoryId(int $value): self
    {
        return $this->whereHas(
            'category',
            static fn (Builder $query) => $query
                ->where('id', '=', $value)
        );
    }
}
