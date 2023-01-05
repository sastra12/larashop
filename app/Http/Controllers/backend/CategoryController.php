<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Image;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\File;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.category.category_index');
    }

    public function data()
    {
        $listdata = Category::all();
        return Datatables::of($listdata)
            // for number
            ->addIndexColumn()
            ->addColumn('action', function ($listdata) {
                return '
               <a href=' . route('category.edit', $listdata->category_slug) . '  class="btn btn-xs btn-info btn-sm">Edit</a>
               <button onclick="deleteData(`' . route('destroy.brand', $listdata->category_slug) . '`)" class="btn btn-sm btn-danger">Delete</button>
           ';
            })
            ->addColumn('category_image', function ($listdata) {
                return '<img src=' . asset('upload/category/' . $listdata->category_image)  . ' width="70px" heigh="40px"/>';
            })
            ->rawColumns(['action', 'category_image'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.category.category_create');
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
            'category_name' => 'required',
            'category_image' => 'required|image|mimes:png,jpg,svg'
        ]);

        if ($request->hasFile('category_image')) {
            $file = $request->file('category_image');
            $filename = date('YmdHi') . '-' . $file->getClientOriginalName();
            Image::make($file)->resize(300, 300)->save('upload/category/' . $filename);

            Category::insert([
                'category_name' => $request->category_name,
                'category_slug' => strtolower(str_replace(' ', '-', $request->category_name)),
                'category_image' => $filename,
            ]);

            return redirect()->route('category.index')->with(notification('Category Inserted Successfully', 'success'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
}
