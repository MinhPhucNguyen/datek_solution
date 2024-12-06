<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductType;

class ProductTypeController extends Controller
{
    public function index()
    {
        $productTypes = ProductType::all();
        return response()->json(
            [
                'product_types' => $productTypes
            ],
            200
        );
    }

    public function store(Request $request)
    {
        $productType = new ProductType();
        $productType->type_name = $request->product_type_name;
        $productType->description = $request->description;
        $productType->save();

        return response()->json(
            [
                'message' => 'Tạo loại sản phẩm thành công.',
                'product-type' => $productType
            ],
            200
        );
    }

    public function edit($id)
    {
        $productType = ProductType::find($id);
        if (!$productType) {
            return response()->json(
                [
                    'message' => 'Không tìm thấy loại sản phẩm.'
                ],
                404
            );
        }

        return response()->json(
            [
                'message' => 'Lấy thông tin loại sản phẩm thành công.',
                'product-type' => $productType
            ],
            200
        );
    }

    public function update(Request $request, $id)
    {
        $productType = ProductType::find($id);
        if (!$productType) {
            return response()->json(
                [
                    'message' => 'Không tìm thấy loại sản phẩm.'
                ],
                404
            );
        }

        $productType->update($request->all());

        return response()->json(
            [
                'message' => 'Cập nhật loại sản phẩm thành công.',
                'product-type' => $productType
            ],
            200
        );
    }

    public function destroy($id)
    {
        $productType = ProductType::find($id);
        if (!$productType) {
            return response()->json(
                [
                    'message' => 'Không tìm thấy loại sản phẩm.'
                ],
                404
            );
        }

        $productType->delete();

        return response()->json(
            [
                'message' => 'Xóa loại sản phẩm thành công.'
            ],
            200
        );
    }
}
