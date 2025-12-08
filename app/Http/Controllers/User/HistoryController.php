<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        $transactions = $user->transactions()->orderBy('created_at', 'desc')->get();
        return view('user.history', compact('transactions'));
    }
}
