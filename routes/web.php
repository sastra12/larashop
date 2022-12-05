<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';

// Admin
Route::middleware(['auth', 'checkrole:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');
    Route::get('/admin/profile', [AdminController::class, 'adminprofile'])->name('admin.profile');
    Route::post('/admin/profile', [AdminController::class, 'adminupdate'])->name('adminprofile.update');
    Route::get('/admin/updatepassword', [AdminController::class, 'changepassword'])->name('change.password');
    Route::post('/admin/updatepassword', [AdminController::class, 'updatepassword'])->name('update.password');
    Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
});

Route::middleware(['auth', 'checkrole:vendor'])->group(function () {
    Route::get('/vendor/dashboard', [VendorController::class, 'VendorDashboard'])->name('vendor.dashboard');
});

Route::get('/admin/login', [AdminController::class, 'login']);
