<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Category $resource
 */
class IndexCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'is_published' => $this->resource->is_published,
            'videos_count' => $this->whenCounted(
                'videos',
                fn (): int => $this->resource->videos_count
            ),
        ];
    }
}
