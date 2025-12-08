<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class POSController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $selectedCategory = $request->get('category_id');
        $search = $request->get('search');

        $products = Product::where('stock', '>', 0)
            ->when($selectedCategory, function ($query) use ($selectedCategory) {
                return $query->where('category_id', $selectedCategory);
            })
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'LIKE', '%' . $search . '%');
            })
            ->get();

        return view('user.pos', compact('categories', 'products', 'selectedCategory', 'search'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'total' => 'required|numeric|min:0',
        ]);

        $items = $request->items;
        $total = $request->total;

        // Verify items and calculate total
        $calculatedTotal = 0;
        $validatedItems = [];

        foreach ($items as $item) {
            $product = Product::find($item['id']);
            if (!$product) {
                return back()->withErrors('Invalid product: ' . $item['name']);
            }
            if ($item['quantity'] > $product->stock) {
                return back()->withErrors('Insufficient stock for: ' . $product->name);
            }
            if ($item['quantity'] <= 0) {
                return back()->withErrors('Invalid quantity for: ' . $product->name);
            }

            $itemTotal = $product->price * $item['quantity'];
            $calculatedTotal += $itemTotal;

            $validatedItems[] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
            ];

            // Update stock
            $product->decrement('stock', $item['quantity']);
        }

        if (abs($calculatedTotal - $total) > 0.01) {
            return back()->withErrors('Total mismatch.');
        }

        Transaction::create([
            'user_id' => Auth::id(),
            'total_price' => $calculatedTotal,
            'items_json' => json_encode($validatedItems),
            'status' => 'pending',
        ]);

        return redirect()->route('user.history')->with('success', 'Order placed successfully.');
    }
}
