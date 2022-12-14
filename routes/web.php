<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\backend\BrandController;
use App\Http\Controllers\backend\CategoryController;
use App\Http\Controllers\backend\SubCategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('frontend.index');
});



Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');
    Route::post('/user/profile', [UserController::class, 'userupdate'])->name('userprofile.update');
    Route::post('/user/logout', [UserController::class, 'logout'])->name('user.logout');
    Route::post('/user/updatepassword', [UserController::class, 'updatepassword'])->name('userpassword.update');
});

require __DIR__ . '/auth.php';

// Admin
Route::middleware(['auth', 'checkrole:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');
    Route::get('/admin/profile', [AdminController::class, 'adminprofile'])->name('admin.profile');
    Route::post('/admin/profile', [AdminController::class, 'adminupdate'])->name('adminprofile.update');
    Route::get('/admin/updatepassword', [AdminController::class, 'changepassword'])->name('change.password');
    Route::post('/admin/updatepassword', [AdminController::class, 'updatepassword'])->name('update.password');
    Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

    // Manage Brand
    Route::controller(BrandController::class)->group(function () {
        // Data Brand
        Route::get('/all/brand', 'index')->name('all.brand');
        Route::get('/all/data_brand', 'data')->name('brand.data');

        // Tambah Brand
        Route::get('/add/brand', 'create')->name('add.brand');
        Route::post('/store/brand', 'store')->name('store.brand');

        // Detail brand
        Route::get('/show/{brand:brand_slug}', 'show')->name('show.brand');
        // Update brand
        Route::post('/update/{brand:brand_slug}', 'update')->name('update.brand');
        // Delete brand
        Route::delete('/delete/{brand:brand_slug}', 'destroy')->name('destroy.brand');
    });

    // Manage Category
    Route::get('/all/data_category', [CategoryController::class, 'data'])->name('category.data');
    Route::get('/category/{category:category_slug}/edit', [CategoryController::class, 'edit'])->name('category.editdata');
    Route::put('/category/{category:category_slug}', [CategoryController::class, 'update'])->name('category.updated');
    Route::resource('category', CategoryController::class)->except(['destroy', 'edit', 'update']);
    Route::delete('/category/{category:category_slug}', [CategoryController::class, 'destroy'])->name('category.delete');


    // Manage SubCategory
    Route::get('/all/data_subcategory', [SubCategoryController::class, 'data'])->name('subcategory.data');
    Route::get('/subcategory/{subcategory:subcategory_slug}/edit', [SUbCategoryController::class, 'edit'])->name('subcategory.editdata');
    Route::put('/subcategory/{subcategory:subcategory_slug}', [SubCategoryController::class, 'update'])->name('subcategory.updated');
    Route::resource('subcategory', SubCategoryController::class)->except(['destroy', 'edit', 'update']);
    Route::delete('/subcategory/{subcategory:subcategory_slug}', [SubCategoryController::class, 'destroy'])->name('subcategory.delete');
});

Route::middleware(['auth', 'checkrole:vendor'])->group(function () {
    Route::get('/vendor/dashboard', [VendorController::class, 'VendorDashboard'])->name('vendor.dashboard');
    Route::get('/vendor/profile', [VendorController::class, 'vendorprofile'])->name('vendor.profile');
    Route::post('/vendor/profile', [VendorController::class, 'vendorupdate'])->name('vendorprofile.update');
    Route::get('/vendor/updatepassword', [VendorController::class, 'changepassword'])->name('change.password');
    Route::post('/vendor/updatepassword', [VendorController::class, 'updatepassword'])->name('update.password');
    Route::post('/vendor/logout', [VendorController::class, 'logout'])->name('vendor.logout');
});

Route::get('/admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::get('/vendor/login', [VendorController::class, 'login'])->name('vendor.login');
