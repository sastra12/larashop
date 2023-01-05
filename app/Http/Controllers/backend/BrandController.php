<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Image;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\File;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.brand.brand_all');
    }

    public function data()
    {
        $listdata = Brand::all();
        return Datatables::of($listdata)
            // for number
            ->addIndexColumn()
            ->addColumn('action', function ($listdata) {
                return '
               <a href=' . route('show.brand', $listdata->brand_slug) . '  class="btn btn-xs btn-info btn-sm">Edit</a>
               <button onclick="deleteData(`' . route('destroy.brand', $listdata->brand_slug) . '`)" class="btn btn-sm btn-danger">Delete</button>
           ';
            })
            ->addColumn('brand_image', function ($listdata) {
                return '<img src=' . asset('upload/brand/' . $listdata->brand_image)  . ' width="70px" heigh="40px"/>';
            })
            ->rawColumns(['action', 'brand_image'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.brand.add_brand');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'brand_name' => 'required',
            'brand_image' => 'required|image|mimes:png,jpg,svg'
        ]);

        if ($request->hasFile('brand_image')) {
            $file = $request->file('brand_image');
            $filename = date('YmdHi') . '-' . $file->getClientOriginalName();
            Image::make($file)->resize(300, 300)->save('upload/brand/' . $filename);

            Brand::insert([
                'brand_name' => $request->brand_name,
                'brand_slug' => strtolower(str_replace(' ', '-', $request->brand_name)),
                'brand_image' => $filename,
            ]);

            return redirect()->route('all.brand')->with(notification('Brand Inserted Successfully', 'success'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        return view('backend.brand.brand_edit', compact('brand'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'brand_name' => 'required',
            'brand_image' => 'image|mimes:png,jpg,svg'
        ]);

        $old_image = $request->old_image;
        if ($request->hasFile('brand_image')) {
            File::delete(public_path('upload/brand/' . $old_image));
            $file = $request->file('brand_image');
            $filename = date('YmdHi') . '-' . $file->getClientOriginalName();
            Image::make($file)->resize(300, 300)->save('upload/brand/' . $filename);
            Brand::where('brand_slug', $brand->brand_slug)
                ->update([
                    'brand_name' => $request->brand_name,
                    'brand_slug' => strtolower(str_replace(' ', '-', $request->brand_name)),
                    'brand_image' => $filename,
                ]);

            return redirect()->route('all.brand')->with(notification('Brand Updated With Image Successfully', 'success'));
        } else {
            Brand::where('brand_slug', $brand->brand_slug)
                ->update([
                    'brand_name' => $request->brand_name,
                    'brand_slug' => strtolower(str_replace(' ', '-', $request->brand_name)),
                ]);

            return redirect()->route('all.brand')->with(notification('Brand Updated Without Image Successfully', 'success'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        $image = public_path('upload/brand/' . $brand->brand_image);
        if (file_exists($image)) {
            unlink($image);
        }
        $brand = Brand::where('brand_slug', $brand->brand_slug);
        $brand->delete();
    }
}
