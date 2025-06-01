<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $users = collect();
        if ($user->isOperator()) {
            $users = User::where('id', '!=', $user->id)->get();
        }
        return view('dashboard', compact('user', 'users'));
    }
}
