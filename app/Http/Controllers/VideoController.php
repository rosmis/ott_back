<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Dto\CreateVideoDto;
use App\Http\Requests\CreateVideoRequest;
use App\Http\Requests\IndexVideoRequest;
use App\Http\Resources\IndexVideoResource;
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

    public function store(CreateVideoRequest $request): JsonResponse
    {
        DB::transaction(
            fn () => $this
                ->videoService
                ->create(
                    CreateVideoDto::fromArray($request->safe()->toArray()),
                    $request->user()
                )
        );

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
