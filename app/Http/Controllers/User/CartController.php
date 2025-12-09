<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;

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

    public function printInvoice(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'total' => 'required|numeric|min:0',
        ]);

        $items = $request->items;
        $total = $request->total;
        $user = auth()->user();

        // Validate items
        $validatedItems = [];
        foreach ($items as $item) {
            $product = Product::find($item['id']);
            if (!$product) {
                return back()->withErrors('Invalid product: ' . $item['name']);
            }

            $validatedItems[] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['price'] * $item['quantity'],
            ];
        }

        // Generate PDF
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);

        $html = view('user.invoice', compact('validatedItems', 'total', 'user'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream('invoice.pdf', ['Attachment' => false]);
    }
}
