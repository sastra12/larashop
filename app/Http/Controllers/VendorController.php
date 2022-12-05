<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\File;

class VendorController extends Controller
{
    //
    public function login()
    {
        return view('vendor.login');
    }

    public function VendorDashboard()
    {
        return view('vendor.index');
    }

    public function vendorprofile()
    {
        $id = Auth::user()->id;
        $data = User::findOrFail($id);
        $data['year'] = getYear($data->created_at);
        return view('vendor.vendor_profile', compact('data'));
    }

    public function vendorupdate(Request $request)
    {
        $id = $request->id;
        $data = User::findOrFail($id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        $data->short_desc = $request->short_desc;

        if ($request->hasFile('photo')) {
            File::delete(public_path('upload/vendor_images/' . $data->photo));
            $file = $request->file('photo');
            $filename = date('YmdHi') . '-' . $file->getClientOriginalName();
            $file->move(public_path('upload/vendor_images'), $filename);
            $data['photo'] = $filename;
        }
        $data->save();
        $notification = [
            'message' => 'Vendor Profile Updated Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->back()->with($notification);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('vendor.login');
    }
}
