<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderCollection;
use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\ShippingAddress;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'address' => 'required|string',
            'payment_method' => 'required|string',
            'total_price' => 'required|numeric',
            'cart_items' => 'required|array',
            'cart_items.*.product_id' => 'required|exists:products,id',
            'cart_items.*.quantity' => 'required|integer|min:1',
            'cart_items.*.price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => $validated['user_id'],
                'order_total_price' => $validated['total_price'],
                'order_status' => 'Chờ xác nhận',
            ]);

            ShippingAddress::create([
                'user_id' => $validated['user_id'],
                'order_id' => $order->order_id,
                'address' => $validated['address'],
            ]);

            foreach ($validated['cart_items'] as $cartItem) {
                $product = Product::findOrFail($cartItem['product_id']);

                if ($product->quantity < $cartItem['quantity']) {
                    DB::rollBack();
                    return response()->json(['message' => 'Số lượng sản phẩm không đủ'], 400);
                }

                $product->quantity -= $cartItem['quantity'];
                $product->save();

                OrderDetails::create([
                    'order_id' => $order->order_id,
                    'product_id' => $cartItem['product_id'],
                    'order_detail_quantity' => $cartItem['quantity'],
                    'order_detail_price' => $cartItem['price'],
                ]);
            }

            Cart::where('user_id', $validated['user_id'])->delete();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }

        DB::commit();
        return response()->json(['message' => 'Đặt hàng thành công', 'order_id' => $order->order_id], 201);
    }

    public function index()
    {
        $paginate = 10;
        $orders = Order::orderBy('order_date', 'desc')->paginate($paginate);
        return new OrderCollection($orders);
    }

    public function getOrderHistory($user_id)
    {
        $orders = Order::where('user_id', $user_id)->get();
        return new OrderCollection($orders);
    }
}
