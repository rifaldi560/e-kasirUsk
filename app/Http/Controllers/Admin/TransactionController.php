<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');
        $transactions = Transaction::with('user')
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $pendingCount = Transaction::where('status', 'pending')->count();
        $completedCount = Transaction::where('status', 'completed')->count();

        return view('admin.transactions.index', compact('transactions', 'pendingCount', 'completedCount'));
    }

    public function complete(Transaction $transaction)
    {
        if ($transaction->status !== 'pending') {
            return back()->withErrors('Transaction is not pending.');
        }

        $transaction->update(['status' => 'completed']);

        return back()->with('success', 'Transaction marked as completed.');
    }

    public function reports()
    {
        $transactions = Transaction::with('user')->where('status', 'completed')->orderBy('updated_at', 'desc')->get();
        return view('admin.transactions.reports', compact('transactions'));
    }
}
