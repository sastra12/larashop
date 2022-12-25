<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function index()
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        // ddd($user->name);
        return view('index', compact('data'));
    }
}
