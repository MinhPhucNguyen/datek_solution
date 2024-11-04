<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductImages;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductImagesController extends Controller
{
    public function index($productId){
        $product = Product::findOrFail($productId);
        $images = $product->productImages()->get();
        
        return response()->json($images);
    }

    public function store(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        $request->validate([
            'image_path' => 'required|string|max:255',
        ]);

        $image = $product->productImages()->create([
            'image_path' => $request->image_path,
        ]);

        return response()->json($image, 201);
    }

    public function show($productId, $id)
    {
        $product = Product::findOrFail($productId);
        $image = $product->productImages()->findOrFail($id);

        return response()->json($image);
    }

    public function update(Request $request, $productId, $id)
    {
        $product = Product::findOrFail($productId);
        $image = $product->productImages()->findOrFail($id);

        $request->validate([
            'image_path' => 'required|string|max:255',
        ]);

        $image->update([
            'image_path' => $request->image_path,
        ]);

        return response()->json($image, 200);
    }

    public function destroy($productId, $id)
    {
        $product = Product::findOrFail($productId);
        $image = $product->productImages()->findOrFail($id);

        $image->delete();

        return response()->json(null, 204);
    }

    public function deleteAll($productId)
    {
        $product = Product::findOrFail($productId);
        $product->productImages()->delete();

        return response()->json(null, 204);
    }
}
