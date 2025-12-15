<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Dompdf\Dompdf;
use Dompdf\Options;

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

        // Store cart data in session for payment method selection
        session([
            'checkout_items' => $items,
            'checkout_total' => $total
        ]);

        return redirect()->route('user.payment');
    }

    public function payment()
    {
        // Retrieve cart data from session
        $items = session('checkout_items');
        $total = session('checkout_total');

        if (!$items || !$total) {
            return redirect()->route('user.cart')->withErrors('No items in cart.');
        }

        return view('user.payment', compact('items', 'total'));
    }

    public function processPayment(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,card,qris,transfer',
        ]);

        $items = session('checkout_items');
        $total = session('checkout_total');

        if (!$items || !$total) {
            return redirect()->route('user.cart')->withErrors('No items in cart.');
        }

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

        // Generate queue number (resets daily)
        $today = now()->toDateString();
        $lastTransaction = Transaction::where('queue_date', $today)
            ->whereNotNull('queue_number')
            ->orderBy('queue_number', 'desc')
            ->first();

        $queueNumber = $lastTransaction ? intval($lastTransaction->queue_number) + 1 : 1;
        $formattedQueueNumber = str_pad($queueNumber, 3, '0', STR_PAD_LEFT);

        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'total_price' => $calculatedTotal,
            'items_json' => json_encode($validatedItems),
            'status' => 'completed',
            'payment_method' => $request->payment_method,
            'queue_number' => $formattedQueueNumber,
            'queue_date' => $today,
        ]);

        // Clear session data
        session()->forget(['checkout_items', 'checkout_total']);

        return redirect()->route('user.receipt', $transaction)->with('auto_download_pdf', true);
    }

    public function receipt(Transaction $transaction)
    {
        // Ensure user can only view their own receipts
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        return view('user.receipt', compact('transaction'));
    }

    public function downloadPdf(Transaction $transaction)
    {
        // Ensure user can only download their own receipts
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        // Configure DomPDF options
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Arial');
        $options->set('isHtml5ParserEnabled', true);

        // Initialize DomPDF
        $dompdf = new Dompdf($options);

        // Load HTML content
        $html = view('user.receipt-pdf', compact('transaction'))->render();

        // Load HTML to DomPDF
        $dompdf->loadHtml($html);

        // Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render PDF
        $dompdf->render();

        // Generate filename
        $filename = 'struk-' . $transaction->id . '-' . now()->format('Y-m-d-H-i-s') . '.pdf';

        // Return PDF as download
        return $dompdf->stream($filename, ['Attachment' => true]);
    }
}
