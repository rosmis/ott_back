<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\IndexVideoRequest;
use App\Http\Resources\IndexVideoResource;
use App\Services\VideoService;
use Symfony\Component\HttpFoundation\JsonResponse;

class VideoController extends Controller
{
    public function __construct(
        private readonly VideoService $videoService
    ) {
    }

    public function index(IndexVideoRequest $request): JsonResponse
    {
        $videos = $this
            ->videoService
            ->list($request->toQuery());

        $videos->loadMissing('category');

        return IndexVideoResource::collection($videos)->response();
    }
}
