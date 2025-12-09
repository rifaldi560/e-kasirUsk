<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            // Always redirect admin users directly to product index
            return redirect()->route('admin.products.index');
        } elseif (Auth::user()->role === 'user') {
            return redirect()->route('user.pos');
        }

        return view('dashboard');
    }
}
