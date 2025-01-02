<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Product $product)
    {
        $reviews = $product->reviews()->where('status', 1)->with('user')->paginate(5);
        return response()->json($reviews);
    }

    public function getReviews()
    {
        $paginate = 10;
        $reviews = Review::with('user', 'product')->paginate($paginate);
        return response()->json($reviews);
    }

    public function store(Request $request, Product $product)
    {
        try {
            $request->validate([
                'review' => 'required|string',
                'rating' => 'required|numeric|min:0|max:5',
            ]);

            $review = new Review;
            $review->review = $request->review;
            $review->rating = $request->rating;
            $review->user_id = auth()->user()->id;
            $review->status = 0;

            $product->reviews()->save($review);

            return response()->json([
                'success' => true,
                'message' => 'Đã thêm đánh giá sản phẩm',
                'review' => $review
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function updateReviewStatus($review_id)
    {
        $review = Review::findOrFail($review_id);
        $review->status = !$review->status;
        $review->save();
        return response()->json([
            'success' => true,
            'message' => 'Đã cập nhật trạng thái đánh giá',
            'review' => $review
        ]);
    }

    public function destroy($review_id)
    {
        $review = Review::findOrFail($review_id);
        $review->delete();
        return response()->json([
            'success' => true,
            'message' => 'Đã xóa đánh giá sản phẩm',
        ]);
    }
}
