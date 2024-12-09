<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'product_id' => $validated['product_id'],
            ],
            [
                'quantity' => DB::raw("quantity + {$validated['quantity']}"),
            ]
        );

        return response()->json(['message' => 'Thêm sản phẩm vào giỏ hàng thành công!', 'cart' => $cart], 200);
    }
    
}
