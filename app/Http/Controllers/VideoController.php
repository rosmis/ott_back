<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Dto\UpdateOrCreateVideoDto;
use App\Http\Requests\CreateVideoRequest;
use App\Http\Requests\IndexVideoRequest;
use App\Http\Requests\UpdateVideoRequest;
use App\Http\Resources\IndexVideoResource;
use App\Http\Resources\VideoResource;
use App\Services\VideoService;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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

        $videos->loadMissing(['category', 'user']);

        return IndexVideoResource::collection($videos)->response();
    }

    public function show(int $video_id): JsonResponse
    {
        $video = $this
            ->videoService
            ->findById($video_id);

        return VideoResource::make($video)->response();
    }

    public function store(CreateVideoRequest $request): JsonResponse
    {
        DB::transaction(
            fn () => $this
                ->videoService
                ->updateOrCreate(
                    UpdateOrCreateVideoDto::fromArray($request->safe()->toArray()),
                    $request->user()
                )
        );

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    public function update(UpdateVideoRequest $request, int $video_id): JsonResponse
    {
        DB::transaction(
            fn () => $this
                ->videoService
                ->updateOrCreate(
                    UpdateOrCreateVideoDto::fromArray($request->safe()->toArray()),
                    $request->user(),
                    $video_id
                )
        );

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    public function destroy(int $video_id): JsonResponse
    {
        $this
            ->videoService
            ->delete($video_id);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
