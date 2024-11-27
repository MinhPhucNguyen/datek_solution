<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    public function getLatestProducts()
    {
        $products = Product::orderBy('created_at', 'desc')->take(10)->get();
        return response()->json($products);
    }

    public function store(Request $request)
    {
        $product = new Product();
        $product->name = $request->name;
        $product->price = $request->price;
        $product->save();
        return response()->json($product);
    }
    public function show(Request $request)
    {
        $productId = $request->input('product_id');
        if (!$productId) {
            return response()->json(['error' => 'Không tìm thấy sản phẩm'], 400);
        }
        $product = Product::findOrFail($productId);
        return new ProductResource($product);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        $product->name = $request->name;
        $product->price = $request->price;
        $product->save();
        return response()->json($product);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();
        return response()->json($product);
    }
}
