<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        // Since cart is managed on frontend, this view shows cart instructions
        return view('user.cart');
    }

    // AJAX endpoint to decrease stock when item added to cart
    public function decreaseStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->product_id);
        if ($product->stock >= $request->quantity) {
            $product->decrement('stock', $request->quantity);
            return response()->json(['success' => true, 'new_stock' => $product->stock]);
        }

        return response()->json(['success' => false, 'message' => 'Insufficient stock']);
    }

    // AJAX endpoint to increase stock when item removed from cart
    public function increaseStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->product_id);
        $product->increment('stock', $request->quantity);

        return response()->json(['success' => true, 'new_stock' => $product->stock]);
    }
}
