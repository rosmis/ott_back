<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\IndexCategoryResource;
use App\Services\CategoryService;
use Symfony\Component\HttpFoundation\JsonResponse;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryService $categoryService
    ) {
    }

    public function index(): JsonResponse
    {
        $categories = $this
            ->categoryService
            ->list();

        return IndexCategoryResource::collection($categories)->response();
    }
}
