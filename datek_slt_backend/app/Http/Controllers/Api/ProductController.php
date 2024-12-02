<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductCollection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImages;

use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    public function index()
    {
        $paginate = 10;
        $search = request('search', '');
        $products = Product::search(trim($search))
            ->with('productImages', 'categories.subCategories')
            ->paginate($paginate);;
        return new ProductCollection($products);
    }

    public function getLatestProducts()
    {
        $products = Product::orderBy('created_at', 'desc')->take(10)->get();
        return response()->json($products);
    }

    public function getProductsBySubCategory(Request $request)
    {
        $subCategorySlug = $request->input('slug');
        $products = Product::where('slug', $subCategorySlug)->get();
        return response()->json($products);
    }

    public function store(ProductRequest $request)
    {
        $validatedData = $request->validated();
        $product = Product::create([
            'sub_category_id' => $validatedData['sub_category_id'],
            'brand_id' => $validatedData['brand_id'],
            'name' => $validatedData['name'],
            'sku' => $validatedData['sku'],
            'quantity' => $validatedData['quantity'],
            'price' => $validatedData['price'],
            'product_type_id' => $validatedData['product_type_id'],
            'status' => $validatedData['status']
        ]);

        if ($validatedData['images']) {
            foreach ($request->file('images') as $image) {
                $cloudinaryImage = $image->storeOnCloudinary('products');
                $uploadedFileUrl = $cloudinaryImage->getSecurePath();
                $publicId = $cloudinaryImage->getPublicId();

                ProductImages::create([
                    'product_id' => $product->id,
                    'image_url' => $uploadedFileUrl,
                    'image_public_id' => $publicId
                ]);
            }
        }

        return response()->json([
            'message' => 'Create car successfully!'
        ], 200);
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
