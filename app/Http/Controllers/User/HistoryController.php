<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Dompdf\Dompdf;
use Dompdf\Options;

class HistoryController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        $transactions = $user->transactions()->orderBy('created_at', 'desc')->get();
        return view('user.history', compact('transactions'));
    }

    public function printInvoice($transactionId)
    {
        $user = Auth::user();
        $transaction = $user->transactions()->findOrFail($transactionId);

        $items = json_decode($transaction->items_json, true);
        $total = $transaction->total_price;

        // Generate PDF
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);

        $html = view('user.invoice', compact('items', 'total', 'user', 'transaction'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream('invoice-' . $transaction->id . '.pdf', ['Attachment' => false]);
    }
}
