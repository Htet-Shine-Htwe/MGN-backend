<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryActionRequest;
use App\Models\Category;
use App\Repo\Admin\Category\CategoryRepo;
use App\Traits\CacheResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{

    use CacheResponse;

    public function __construct(protected CategoryRepo $categoryRepo,private string $cacheKey = "")
    {
        $this->cacheKey = $this->generateCacheKey('all-categories');

    }

    public function all(Request $request): JsonResponse
    {
        $key = $this->cacheKey;
        $categories = $this->cacheResponse(
            $key, 300, function () use ($request) {
                return $this->categoryRepo->get($request);
            }
        );

        return response()->json(
            [
            'categories' => $categories
            ]
        );
    }

    public function index(Request $request)  : JsonResponse
    {
        $categories =  $this->categoryRepo->get($request);

        return response()->json(
            [
            'categories' => $categories
            ]
        );
    }

    public function create(CategoryActionRequest $request)  : JsonResponse
    {
        $category = $this->categoryRepo->create($request);
        $key = $this->cacheKey;

        $this->forgetCache($key);
        return response()->json(
            [
            'category' => $category,
            'message' => 'Category created successfully.'
            ], Response::HTTP_CREATED
        );
    }

    public function update(CategoryActionRequest $request,Category $category)  : JsonResponse
    {
        $updated_category = $this->categoryRepo->update($request, $category);
        $key = $this->cacheKey;
        $this->forgetCache($key);

        return response()->json(
            [
            'category' => $updated_category,
            'message' => 'Category updated successfully.'
            ], Response::HTTP_OK
        );
    }

    public function delete(Category $category)  : JsonResponse
    {
        $this->categoryRepo->delete($category);
        $key = $this->cacheKey;
        $this->forgetCache($key);

        return response()->json(
            [
            'message' => 'Category deleted successfully.'
            ], Response::HTTP_OK
        );
    }


}
