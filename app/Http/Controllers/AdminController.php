<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    //
    public function login()
    {
        return view('admin.login');
    }

    public function AdminDashboard()
    {
        return view('admin.index');
    }

    public function adminprofile()
    {
        $id = Auth::user()->id;
        $data = User::findOrFail($id);
        return view('admin.admin_profile', compact('data'));
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
