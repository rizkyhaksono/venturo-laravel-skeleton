<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Product\ProductCategoryHelper;
use App\Http\Resources\Product\CategoryResource;
use App\Http\Resources\Product\CategoryCollection;
use App\Http\Requests\Product\CategoryRequest;

class ProductCategoryController extends Controller
{
    private $category;
    public function __construct()
    {
        $this->category = new ProductCategoryHelper();
    }

    public function destroy($id)
    {
        $category = $this->category->delete($id);

        if (!$category) {
            return response()->failed(['Mohon maaf category tidak ditemukan']);
        }

        return response()->success($category, 'category berhasil dihapus');
    }

    public function index(Request $request)
    {
        $filter = [
            'name' => $request->name ?? '',
        ];
        $categories = $this->category->getAll($filter, $request->page ?? 1,  $request->per_page ?? 25, $request->sort ?? '');

        return response()->success(new CategoryCollection($categories['data']));
    }

    public function show($id)
    {
        $category = $this->category->getById($id);

        if (!($category['status'])) {
            return response()->failed(['Data category tidak ditemukan'], 404);
        }

        return response()->success(new CategoryResource($category['data']));
    }

    public function store(CategoryRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['name']);
        $category = $this->category->create($payload);

        if (!$category['status']) {
            return response()->failed($category['error']);
        }

        return response()->success(new CategoryResource($category['data']), 'category berhasil ditambahkan');
    }

    public function update(CategoryRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['name', 'id']);
        $category = $this->category->update($payload, $payload['id'] ?? 0);

        if (!$category['status']) {
            return response()->failed($category['error']);
        }

        return response()->success(new CategoryResource($category['data']), 'category berhasil diubah');
    }
}
