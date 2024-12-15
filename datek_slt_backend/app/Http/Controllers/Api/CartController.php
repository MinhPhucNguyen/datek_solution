<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function getCart(Request $request)
    {
        $cartItems = Cart::where('user_id', $request->user_id)
            ->with(['product', 'product.productImages'])
            ->get();
        return response()->json(['items' => $cartItems]);
    }
    public function checkProduct(Request $request)
    {
        $cart = Cart::where('user_id', $request->user_id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cart) {
            return response()->json(['exists' => true, 'cart_id' => $cart->id]);
        }

        return response()->json(['exists' => false]);
    }

    public function updateQuantity(Request $request)
    {
        if (!is_numeric($request->quantity)) {
            return response()->json(['success' => false, 'message' => 'Số lượng không hợp lệ.'], 400);
        }

        $cartItem = Cart::where('product_id', $request->product_id)->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 400);
    }

    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::where('user_id', $validated['user_id'])
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($cart) {
            $cart->increment('quantity', $validated['quantity']);
        } else {
            $cart = Cart::create([
                'user_id' => $validated['user_id'],
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
            ]);
        }

        return response()->json(['message' => 'Thêm sản phẩm vào giỏ hàng thành công!', 'cart' => $cart], 200);
    }

    public function removeItem(Request $request)
    {
        $cart = Cart::find($request->cart_id);

        if ($cart) {
            $cart->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 400);
    }

    public function countItems(Request $request)
    {
        $count = Cart::where('user_id', $request->user_id)->count();
        return response()->json(['count' => $count]);
    }
}
