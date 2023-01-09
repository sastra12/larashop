<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use App\Models\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.subcategory.subcategory_index');
    }

    public function data()
    {
        $listdata = SubCategory::with('category')->get();
        // ddd($listdata);
        return Datatables::of($listdata)
            // for number
            ->addIndexColumn()
            ->addColumn('action', function ($listdata) {
                return '
               <a href=' . route('subcategory.editdata', $listdata->subcategory_slug) . '  class="btn btn-xs btn-info btn-sm">Edit</a>
               <button onclick="deleteData(`' . route('subcategory.delete', $listdata->subcategory_slug) . '`)" class="btn btn-sm btn-danger">Delete</button>
           ';
            })
            ->addColumn('subcategory_name', function ($listdata) {
                return $listdata->subcategory_name;
            })
            ->addColumn('category_name', function ($listdata) {
                return $listdata->category->category_name;
            })
            ->rawColumns(['action', 'category_name', 'subcategory_name'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::orderBy('category_name', 'ASC')->get();
        return view('backend.subcategory.subcategory_create', compact('categories'));
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
            'category_id' => 'required|not_in:0',
            'subcategory_name' => 'required'
        ]);

        SubCategory::insert([
            'category_id' => $request->category_id,
            'subcategory_name' => $request->subcategory_name,
            'subcategory_slug' => strtolower(str_replace(' ', '-', $request->subcategory_name)),
        ]);

        return redirect()->route('subcategory.index')->with(notification('SubCategory Inserted Successfully', 'success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SubCategory  $subCategory
     * @return \Illuminate\Http\Response
     */
    public function show(SubCategory $subcategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SubCategory  $subCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(SubCategory $subcategory)
    {
        $data = SubCategory::where('subcategory_slug', $subcategory->subcategory_slug)->first();
        $categories = Category::orderBy('category_name', 'ASC')->get();
        return view('backend.subcategory.subcategory_edit', compact('categories', 'data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SubCategory  $subCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubCategory $subcategory)
    {
        $request->validate([
            'category_id' => 'required|not_in:0',
            'subcategory_name' => 'required'
        ]);

        SubCategory::where('subcategory_slug', $subcategory->subcategory_slug)
            ->update([
                'category_id' => $request->category_id,
                'subcategory_name' => $request->subcategory_name,
                'subcategory_slug' => strtolower(str_replace(' ', '-', $request->subcategory_name)),
            ]);

        return redirect()->route('subcategory.index')->with(notification('SubCategory Updated Successfully', 'success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SubCategory  $subCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubCategory $subcategory)
    {
        $category = SubCategory::where('subcategory_slug', $subcategory->subcategory_slug);
        $category->delete();
    }
}
