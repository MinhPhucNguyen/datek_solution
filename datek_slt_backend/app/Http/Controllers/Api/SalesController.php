<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sales;
use App\Models\Cart;

class SalesController extends Controller
{

    public function index()
    {
        try {
            $paginate = 10;
            $sales = Sales::orderBy('created_at', 'desc')->paginate($paginate);
            return response()->json($sales, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi lấy danh sách mã giảm giá.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function createCouponCode(Request $request)
    {
        $validated = $request->validate([
            'sale_name' => 'required',
            'coupon_code' => 'required|string',
            'sale_percentage' => 'required|numeric|min:1|max:100',
            'sale_begin_at' => 'required|date',
            'sale_end_at' => 'required|date',
        ], [
            'sale_name.required' => '*Vui lòng nhập tên mã giảm giá.',
            'coupon_code.required' => '*Vui lòng nhập mã giảm giá',
            'sale_percentage.required' => '*Vui lòng nhập mức giảm giá.',
            'sale_percentage.numeric' => '*Mức giảm giá phải là số.',
            'sale_percentage.min' => '*Mức giảm giá phải lớn hơn 0.',
            'sale_percentage.max' => '*Mức giảm giá không được lớn hơn 100.',
            'sale_begin_at.required' => '*Vui lòng nhập ngày bắt đầu.',
            'sale_begin_at.date' => '*Ngày bắt đầu không hợp lệ.',
            'sale_end_at.required' => '*Vui lòng nhập ngày kết thúc.',
            'sale_end_at.date' => '*Ngày kết thúc không hợp lệ.',
        ]);

        $sale = new Sales();
        $sale->sale_name = $validated['sale_name'];
        $sale->coupon_code = $validated['coupon_code'];
        $sale->sale_percentage = $validated['sale_percentage'];
        $sale->sale_begin_at = $validated['sale_begin_at'];
        $sale->sale_end_at = $validated['sale_end_at'];
        $sale->is_active = true;
        $sale->save();

        return response()->json(['message' => 'Tạo mã giảm giá thành công.'], 200);
    }

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

    public function getDiscountById($saleId)
    {
        try {
            $sale = Sales::find($saleId);
            if (!$sale) {
                return response()->json(['message' => 'Không tìm thấy mã giảm giá.'], 404);
            }
            return response()->json($sale, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi lấy thông tin mã giảm giá.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'sale_name' => 'required',
                'coupon_code' => 'required|string',
                'sale_percentage' => 'required|numeric|min:1|max:100',
                'sale_begin_at' => 'required|date',
                'sale_end_at' => 'required|date',
            ], [
                'sale_name.required' => '*Vui lòng nhập tên mã giảm giá.',
                'coupon_code.required' => '*Vui lòng nhập mã giảm giá',
                'sale_percentage.required' => '*Vui lòng nhập mức giảm giá.',
                'sale_percentage.numeric' => '*Mức giảm giá phải là số.',
                'sale_percentage.min' => '*Mức giảm giá phải lớn hơn 0.',
                'sale_percentage.max' => '*Mức giảm giá không được lớn hơn 100.',
                'sale_begin_at.required' => '*Vui lòng nhập ngày bắt đầu.',
                'sale_begin_at.date' => '*Ngày bắt đầu không hợp lệ.',
                'sale_end_at.required' => '*Vui lòng nhập ngày kết thúc.',
                'sale_end_at.date' => '*Ngày kết thúc không hợp lệ.',
            ]);

            $sale = Sales::find($request->id);
            $sale->sale_name = $validated['sale_name'];
            $sale->coupon_code = $validated['coupon_code'];
            $sale->sale_percentage = $validated['sale_percentage'];
            $sale->sale_begin_at = $validated['sale_begin_at'];
            $sale->sale_end_at = $validated['sale_end_at'];
            $sale->is_active = true;
            $sale->save();

            return response()->json(['message' => 'Cập nhật mã giảm giá thành công.'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi cập nhật mã giảm giá.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($saleId)
    {
        try {
            $sale = Sales::find($saleId);
            if (!$sale) {
                return response()->json(['message' => 'Không tìm thấy mã giảm giá.'], 404);
            }
            $sale->delete();
            return response()->json(['message' => 'Xóa mã giảm giá thành công.'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra khi xóa mã giảm giá.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
