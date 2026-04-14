<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Video $resource
 */
class IndexVideoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'duration' => $this->resource->duration_seconds,
            'status' => $this->resource->status->value,
            'thumbnail_url' => $this->resource->thumbnail_url,
            'category' => $this->whenLoaded(
                'category',
                static fn (): array => [
                    'id' => $this->resource->category->id,
                    'name' => $this->resource->category->name,
                ]
            ),
            'published_at' => $this->resource->published_at,
        ];
    }
}
