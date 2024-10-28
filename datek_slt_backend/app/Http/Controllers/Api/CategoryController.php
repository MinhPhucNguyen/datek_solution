<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Resources\CategoryCollection;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return new CategoryCollection($categories);
    }

    public function store(Request $request)
    {
        $category = new Category();
        $category->category_name = $request->category_name;
        $category->description = $request->description;
        $category->save();

        return response()->json(
            [
                'message' => 'Tạo danh mục thành công.',
                'category' => $category
            ],
            200
        );
    }

    public function edit($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(
                [
                    'message' => 'Không tìm thấy danh mục.'
                ],
                404
            );
        }

        return response()->json(
            [
                'category' => $category
            ],
            200
        );
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(
                [
                    'message' => 'Không tìm thấy danh mục.'
                ],
                404
            );
        }

        $category->update($request->all());

        return response()->json(
            [
                'message' => 'Cập nhật danh mục thành công.',
                'category' => $category
            ],
            200
        );
    }
}
