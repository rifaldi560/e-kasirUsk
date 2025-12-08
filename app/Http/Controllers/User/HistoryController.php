<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index()
    {
        $transactions = auth()->user()->transactions()->orderBy('created_at', 'desc')->get();
        return view('user.history', compact('transactions'));
    }
}
