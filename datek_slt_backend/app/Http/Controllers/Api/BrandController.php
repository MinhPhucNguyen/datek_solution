<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::orderBy('id', 'desc')->get();
        return response()->json([
            'brands' => $brands
        ], 200);
    }

    public function getBrandById($brandId)
    {
        $brand = Brand::findOrFail($brandId);
        if (!$brand) {
            return response()->json(
                [
                    'message' => 'Không tìm thấy!',
                ],
                404
            );
        }
        return response()->json(
            [
                'brand' => $brand
            ],
            200
        );
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'brand_name' => 'required|string|unique:brands,brand_name',
        ]);


        $filename = null;
        if ($request->hasFile('logo')) {
            $logoImage = $request->file('logo');
            $filename = $logoImage->getClientOriginalName();
            $logoImage->storeAs('brandimages/' . $filename);
        }

        $brand = Brand::create([
            'brand_name' => Str::upper($validatedData['brand_name']),
            'status' => $request->status
        ]);

        if ($request->hasFile('logo')) {
            $cloudinaryImage = $request->file('logo')->storeOnCloudinary('brandimages');
            $uploadedFileUrl = $cloudinaryImage->getSecurePath();
            $publicId = $cloudinaryImage->getPublicId();

            $brand->update([
                'brand_logo' => $uploadedFileUrl,
                'brand_logo_public_id' => $publicId,
            ]);
        }

        return response()->json(
            [
                'message' => 'Create successfully!',
                'brand' => $brand
            ],
            200
        );
    }

    public function update(int $brand_id, Request $request)
    {
        $validatedData = $request->validate([
            'brand_name' => 'required|string|unique:brands,brand_name,' . $brand_id . ',id'
        ]);

        $brand = Brand::findOrFail($brand_id);

        if (!$brand) {
            return response()->json(
                [
                    'message' => 'Không tìm thấy!',
                ],
                404
            );
        }

        $brand->update([
            'brand_name' => $validatedData['brand_name'],
            'status' => $request->status
        ]);

        if ($request->hasFile('logo')) {
            if ($brand->brand_logo_public_id) {
                \CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary::destroy($brand->brand_logo_public_id);
            }

            $cloudinaryImage = $request->file('logo')->storeOnCloudinary('brandimages');
            $uploadedFileUrl = $cloudinaryImage->getSecurePath();
            $publicId = $cloudinaryImage->getPublicId();

            $brand->update([
                'brand_logo' => $uploadedFileUrl,
                'brand_logo_public_id' => $publicId,
            ]);
        }

        return response()->json(
            [
                'message' => 'Cập nhật thành công!',
            ],
            200
        );
    }

    public function delete(int $brand_id)
    {
        $brand =    Brand::findOrFail($brand_id);
        if (!$brand) {
            return response()->json(
                [
                    'message' => 'Không có sản phẩm nào tồn tại!',
                ],
                404
            );
        }

        if ($brand->brand_logo_public_id) {
            \CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary::destroy($brand->brand_logo_public_id);
        }

        $brand->delete();
        return response()->json(
            [
                'message' => 'Xóa hãng thành công!',
            ],
            200
        );
    }
}
