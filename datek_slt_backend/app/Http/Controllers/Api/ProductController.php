<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductCollection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImages;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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

    public function getProductsByCategory(Request $request)
    {
        $subCategorySlug = $request->input('slug');
        $products = Product::where('slug', $subCategorySlug)->get();
        return response()->json($products);
    }

    public function createProduct(ProductRequest $request)
    {
        $validatedData = $request->validated();
        $product = Product::create([
            'brand_id' => $validatedData['brand_id'],
            'name' => $validatedData['name'],
            'sku' => $validatedData['sku'],
            'quantity' => $validatedData['quantity'],
            'price' => $validatedData['price'],
            'product_type_id' => $validatedData['product_type_id'],
            'status' => $request->boolean('status', true) ? 1 : 0,
            'description' => $request->input('description')
        ]);

        $product->categories()->sync($validatedData['category_ids']);

        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $image) {
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
            'message' => 'Tạo sản phẩm thành công',
            'product' => $product
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

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        if (!$product) {
            return response()->json([
                'message' => 'Không tìm thấy sản phẩm.'
            ], 404);
        }
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
        if (!$product) {
            return response()->json([
                'message' => 'Không tìm thấy sản phẩm.'
            ], 404);
        }
        $productImages = ProductImages::where('product_id', $product->id)->get();
        $this->removeProductImagesInCloudinary($productImages);
        ProductImages::where('product_id', $product->id)->delete();
        $product->delete();
        return response()->json($product);
    }

    public function deleteMultiProduct($products)
    {
        $productsIdArray = explode(',', $products);
        $products = Product::whereIn('id', $productsIdArray)->get();
        foreach ($products as $product) {
            $productImages = ProductImages::where('product_id', $product->id)->get();
            $this->removeProductImagesInCloudinary($productImages);
            ProductImages::where('product_id', $product->id)->delete();
            $product->delete();
        }
        return response()->json([
            'message' => 'Xóa ' . count($productsIdArray) . ' sản phẩm thành công.'
        ], 200);
    }

    protected function removeProductImagesInCloudinary($images)
    {
        if (!empty($images)) {
            foreach ($images as $image) {
                if ($image->image_public_id) {
                    Cloudinary::destroy($image->image_public_id);
                }
            }
        }
    }
}
