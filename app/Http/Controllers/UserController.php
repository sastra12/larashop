<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

    public function userupdate(Request $request)
    {
        $id = $request->id;
        $data = User::findOrFail($id);
        $data->name = $request->name;
        $data->username = $request->username;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;

        if ($request->hasFile('photo')) {
            File::delete(public_path('upload/user_images/' . $data->photo));
            $file = $request->file('photo');
            $filename = date('YmdHi') . '-' . $file->getClientOriginalName();
            $file->move(public_path('upload/user_images'), $filename);
            $data['photo'] = $filename;
        }
        $data->save();
        $notification = [
            'message' => 'User Profile Updated Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($notification);
    }

    public function updatepassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|confirmed'
        ]);

        if (Hash::check($request->old_password, Auth::user()->password)) {
            User::where('id', $request->id)->update([
                'password' => Hash::make($request->password)
            ]);
            return back()->with("success", "Password Changed Successfully");
        } else {
            return back()->with("error", "Old Password Doesn't Match!!");
        }
    }


    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
