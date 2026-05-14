<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function users()
    {
        $users = \App\Models\User::latest()->get();

        return view('admin.users.index', compact('users'));
    }

    public function dashboard()
    {
        $totalUsers = \App\Models\User::count();

        $pendingApprovals = \App\Models\User::where('is_approved', false)->count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'pendingApprovals'
        ));
    }
}
