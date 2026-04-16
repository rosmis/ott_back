<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Video $resource
 */
class VideoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'slug' => $this->resource->slug,
            'duration' => $this->resource->duration_seconds,
            'status' => $this->resource->status->value,
            'thumbnail_url' => $this->resource->thumbnail_url,
            'video_url' => $this->resource->video_url,
            'category_id' => $this->resource->category_id,
        ];
    }
}
