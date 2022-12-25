<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function index()
    {
        $id = Auth::user()->id;
        $user = User::find($id);
        return view('index', compact('user'));
    }
}
