<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('subCategories')->whereNull('parent_id')->get();
        return response()->json([
            'categories' => $categories
        ]);
    }

    public function show($id)
    {
        $category = Category::with('children', 'products')->findOrFail($id);
        return response()->json($category);
    }


    public function store(Request $request)
    {

        $request->validate(
            [
                'slug' => 'required|string',
                'category_name' => 'required|string|max:255',
                'parent_id' => 'nullable|exists:categories,id',
            ],
            [
                'slug.required' => 'Slug là bắt buộc.',
                'slug.string' => 'Slug phải là chuỗi ký tự.',
                'category_name.required' => 'Tên danh mục là bắt buộc.',
                'category_name.string' => 'Tên danh mục phải là chuỗi ký tự.',
                'category_name.max' => 'Tên danh mục không được vượt quá 255 ký tự.',
            ]
        );

        $category = new Category();
        $category->category_name = $request->category_name;
        $category->description = $request->description;
        $category->slug = $request->slug;
        $category->parent_id = $request->parent_id;
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

    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        if (!$category) {
            return response()->json(
                [
                    'message' => 'Không tìm thấy danh mục.'
                ],
                404
            );
        }

        $validatedData = $request->validate(
            [
                'slug' => 'required|string',
                'category_name' => 'required|string|max:255',
            ],
            [
                'slug.required' => 'Slug là bắt buộc.',
                'slug.string' => 'Slug phải là chuỗi ký tự.',
                'category_name.required' => 'Tên danh mục là bắt buộc.',
                'category_name.string' => 'Tên danh mục phải là chuỗi ký tự.',
                'category_name.max' => 'Tên danh mục không được vượt quá 255 ký tự.',
            ]
        );

        $category->update([
            'category_name' => $validatedData['category_name'],
            'slug' => $validatedData['slug'],
            'status' => $request->status,
            'description' => $request->description ?? "",
            'parent_id' => $request->parent_id === 'NULL' ? NULL : $request->parent_id
        ]);

        return response()->json(
            [
                'message' => 'Cập nhật danh mục thành công.',
                'category' => $category
            ],
            200
        );
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(['message' => 'Xóa danh mục thành công']);
    }
}
