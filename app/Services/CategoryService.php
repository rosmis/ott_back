<?php

declare(strict_types=1);

namespace App\Services;

use App\Actions\SaveVideoAction;
use App\Actions\SaveVideoThumbnailAction;
use App\Actions\UpdateOrCreateVideoAction;
use App\Dto\UpdateOrCreateVideoDto;
use App\Models\Category;
use App\Models\User;
use App\Models\Video;
use App\Queries\VideoQuery;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Sylarele\HttpQueryConfig\Contracts\QueryResult;

readonly class CategoryService
{
    /**
     * @return Collection<int, Category>
     */
    public function list(): Collection
    {
        return Category::query()
            ->withCount('videos')
            ->get();
    }
}
