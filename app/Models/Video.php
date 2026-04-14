<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\VideoBuilder;
use App\Enums\VideoStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property int $category_id
 * @property string|null $description
 * @property int|null $duration_seconds
 * @property string|null $thumbnail_url
 * @property string|null $video_url
 * @property VideoStatus $status
 * @property ?Carbon $published_at
 * @property Carbon $updated_at
 * @property Carbon $created_at
 *
 * Relations
 * @property Category $category
 *
 *     - Support.
 *
 * @method VideoBuilder newQuery()
 * @method static VideoBuilder query()
 */
class Video extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'status' => VideoStatus::class,
        'published_at' => 'datetime',
    ];

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @param  QueryBuilder  $query
     */
    public function newEloquentBuilder($query): VideoBuilder
    {
        return new VideoBuilder($query);
    }
}
