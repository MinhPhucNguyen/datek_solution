<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sales;
use App\Models\Cart;

class SalesController extends Controller
{
    public function applyDiscount(Request $request)
    {
        try {
            $validated = $request->validate([
                'coupon_code' => 'required|string'
            ]);

            $sale = Sales::where('coupon_code', $validated['coupon_code'])
                ->where('is_active', true)
                ->where('sale_begin_at', '<=', now())
                ->where('sale_end_at', '>=', now())
                ->first();

            if (!$sale) {
                return response()->json(['message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn. Vui lòng nhập mã giảm giá khác.'], 400);
            }

            $discountPercentage = $sale->sale_percentage;

            $cartItems = Cart::where('user_id', $request->user_id)->get();

            $discounts = [];
            $totalBeforeDiscount = 0;
            $totalDiscountAmount = 0;

            foreach ($cartItems as $item) {
                $product = $item->product;

                $productPrice = $product->price * $item->quantity;
                $discountAmountPerProduct = $productPrice * ($discountPercentage / 100);

                $totalBeforeDiscount += $productPrice;
                $totalDiscountAmount += $discountAmountPerProduct;

                $discounts[] = [
                    'product_id' => $product->id,
                    'discount_amount' => $discountAmountPerProduct,
                    'product_name' => $product->name,
                    'final_price' => $productPrice - $discountAmountPerProduct,
                ];
            }

            $totalAfterDiscount = $totalBeforeDiscount - $totalDiscountAmount;

            return response()->json(
                [
                    'message' => 'Áp dụng mã giảm giá thành công.',
                    'discounts' => $discounts,
                    'total_before_discount' => $totalBeforeDiscount,
                    'total_discount' => $totalDiscountAmount,
                    'total_after_discount' => $totalAfterDiscount
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi áp dụng mã giảm giá.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
