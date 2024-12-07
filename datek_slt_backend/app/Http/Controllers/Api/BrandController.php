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
            'logo' => $filename,
            'brand_name' => Str::upper($validatedData['brand_name']),
            'status' => $request->status
        ]);

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
            'brand_name' => 'required|string|unique:brands,brand_name,' . $brand_id . ',brand_id'
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
            $cloudinaryImage = $request->file('logo')->storeOnCloudinary('brandimages');
            $uploadedFileUrl = $cloudinaryImage->getSecurePath();
            $publicId = $cloudinaryImage->getPublicId();
            $brand->logo = $uploadedFileUrl;
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
                    'message' => 'Brand not found!',
                ],
                404
            );
        }
        if ($brand->logo) {
            
        }
        $brand->delete();
        return response()->json(
            [
                'message' => 'Delete successfully!',
            ],
            200
        );
    }
}
