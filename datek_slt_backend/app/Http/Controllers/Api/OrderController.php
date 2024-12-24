<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\ShippingAddress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
                'order_date' => now(),
                'payment_method' => $validated['payment_method'],
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

    public function index(Request $request)
    {
        $paginate = 10;


        $orders = Order::orderBy('order_date', 'desc')->paginate($paginate);
        return new OrderCollection($orders);
    }

    public function getOrderHistory(Request $request)
    {
        $paginate = 10;

        $userId = $request->input('user_id');

        $orders = Order::where('user_id', $userId)
            ->orderBy('order_date', 'desc')
            ->paginate($paginate);
        return new OrderCollection($orders);
    }

    public function getOrderDetails($orderId)
    {
        $order = Order::findOrFail($orderId);
        return new OrderResource($order);
    }

    public function confirmOrder($orderId)
    {
        $order = Order::findOrFail($orderId);

        if (!$order) {
            return response()->json(['message' => 'Đơn hàng không tồn tại'], 404);
        }

        switch ($order->order_status) {
            case 'Chờ xác nhận':
                $order->order_status = 'Chờ giao hàng';
                $order->save();
                return response()->json(['message' => 'Xác nhận đơn hàng thành công'], 200);

            case 'Chờ giao hàng':
                $order->order_status = 'Đã giao';
                $order->save();
                return response()->json(['message' => 'Xác nhận giao hàng thành công'], 200);
            default:
                return response()->json(['message' => 'Không thể xử  lý đơn hàng'], 400);
        }
    }

    public function cancelOrder($orderId)
    {
        $order = Order::findOrFail($orderId);

        if (!$order) {
            return response()->json(['message' => 'Đơn hàng không tồn tại'], 404);
        }

        if ($order->order_status === 'Đã giao') {
            return response()->json(['message' => 'Không thể hủy đơn hàng đã được xác nhận hoặc đang giao hàng'], 400);
        }

        foreach ($order->orderDetails as $item) {
            $product = $item->product; 
            if ($product) {
                $product->quantity += $item->quantity; 
                $product->save();
            }
        }

        $order->order_status = 'Đã hủy';
        $order->save();

        return response()->json(['message' => 'Đơn hàng đã được hủy thành công và số lượng sản phẩm đã được hoàn lại'], 200);
    }
}
